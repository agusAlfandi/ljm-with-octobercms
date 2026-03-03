<?php namespace Agus\Tabler\Models;

use Model;
use Agus\Tabler\Classes\GoogleDriveReader;
use Agus\Tabler\Classes\GoogleDriveUploader;

/**
 * Model
 */
class Grafik_kepuasan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \Agus\Tabler\Traits\SpmiOptions;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_grafik_kepuasan';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'title'   => 'required',
        'periode' => 'required',
        'prodi'   => 'required',
        'file'    => 'required'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'periode',
        'prodi',
        'periode_name',
        'prodi_name',
        'file'
    ];

    public function getDisplayPeriodeAttribute()
    {
        return $this->periode_name ?: $this->periode;
    }

    public function getDisplayProdiAttribute()
    {
        return $this->prodi_name ?: $this->prodi;
    }

    public function beforeSave()
    {
        $this->periode_name = $this->periode;
        $this->prodi_name   = $this->prodi;
    }

    /**
     * @var array Attributes to attach files.
     */
    public $attachOne = [
        'file' => 'System\Models\File'
    ];

    /**
     * @var bool Flag to prevent recursive afterSave calls
     */
    protected $isUploadingToGoogleDrive = false;


    /**
     * afterSave - trigger upload setelah semua relasi tersimpan
     */
    public function afterSave()
    {
        // Skip jika sedang dalam proses upload (mencegah recursive call)
        if ($this->isUploadingToGoogleDrive) {
            return;
        }

        if ($this->file()->withDeferred($this->sessionKey)->count() > 0) {
            $file = $this->file()->withDeferred($this->sessionKey)->first();
            if ($file && $this->periode && $this->prodi && $this->title) {
                $this->deleteOldFileIfExists();
                $this->uploadFileToGoogleDrive($file);
            }
        } elseif ($this->file && $this->periode && $this->prodi && $this->title) {
            $this->deleteOldFileIfExists();
            $this->uploadFileToGoogleDrive($this->file);
        }
    }

    /**
     * Helper method untuk upload file ke Google Drive
     */
    protected function uploadFileToGoogleDrive($file)
    {
        try {
            $filePath = $file->getLocalPath();
            $fileName = $file->file_name;

            $targetFolderId = $this->getTargetFolderId('ROOT_GRAFIK_KEPUASAN');
            if (!$targetFolderId) {
                \Log::error('Grafik Kepuasan: unable to resolve target folder for ' . $this->periode . '/' . $this->prodi);
                return;
            }

            $result = GoogleDriveUploader::upload($filePath, $targetFolderId, $fileName);

            if (isset($result['fileId'])) {
                $this->isUploadingToGoogleDrive = true;

                $uploadedFileName = $result['fileName'] ?? $fileName;
                if (!preg_match('/\.pdf$/i', $uploadedFileName)) {
                    $uploadedFileName .= '.pdf';
                }
                $this->file_name = $uploadedFileName;
                $this->rules = [];
                $this->save();

                $this->isUploadingToGoogleDrive = false;

                \Cache::forget('gdrive_structure_' . md5(GoogleDriveReader::FOLDER_IDS['ROOT_GRAFIK_KEPUASAN']));

                $this->deleteLocalFileRelation($file, $filePath);
            } else {
                \Log::warning('Grafik Kepuasan upload response missing fileId', ['response' => $result]);
            }
        } catch (\Exception $e) {
            $this->isUploadingToGoogleDrive = false;
            \Log::error('Grafik Kepuasan Google Drive upload failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    /**
     * Hapus file lama di Google Drive sebelum upload file baru
     */
    protected function deleteOldFileIfExists()
    {
        $oldFileName = $this->getOriginal('file_name');
        $oldFolderId = $this->getTargetFolderId('ROOT_GRAFIK_KEPUASAN');

        if ($oldFileName && $oldFolderId) {
            $existingFile = GoogleDriveReader::findFileByName($oldFolderId, $oldFileName);
            if ($existingFile) {
                GoogleDriveUploader::delete($existingFile['id']);
            }
        }
    }


    /**
     * Hapus relasi file lokal setelah upload sukses
     */
    protected function deleteLocalFileRelation($file, $filePath)
    {
        try {
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $file->delete();
        } catch (\Exception $e) {
            \Log::error('Grafik Kepuasan: Failed to delete local file', [
                'error' => $e->getMessage(),
                'filePath' => $filePath
            ]);
        }
    }

    public function afterDelete()
    {
        if ($this->file_name) {
            try {
                $targetFolderId = $this->getTargetFolderId('ROOT_GRAFIK_KEPUASAN');
                if ($targetFolderId) {
                    $existingFile = GoogleDriveReader::findFileByName($targetFolderId, $this->file_name);
                    if ($existingFile) {
                        GoogleDriveUploader::delete($existingFile['id']);
                    }
                }
                \Cache::forget('gdrive_structure_' . md5(GoogleDriveReader::FOLDER_IDS['ROOT_GRAFIK_KEPUASAN']));
            } catch (\Exception $e) {
                \Log::error('Grafik Kepuasan Google Drive delete failed: ' . $e->getMessage());
            }
        }
    }

}
