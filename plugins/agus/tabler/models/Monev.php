<?php namespace Agus\Tabler\Models;

use Model;
use Agus\Tabler\Classes\GoogleDriveUploader;
use Agus\Tabler\Classes\GoogleDriveReader;
use Agus\Tabler\Traits\SpmiOptions;

/**
 * Model
 */
class Monev extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use SpmiOptions;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_monev';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'title'    => 'required',
        'category' => 'required',
        'periode'  => 'required',
        'prodi'    => 'required',
        'file'     => 'required'
    ];

    /**
     * @var array Attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'category',
        'periode',
        'prodi',
        'periode_name',
        'prodi_name',
        'file'
    ];

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
     * Set periode_name and prodi_name before saving
     */
    public function beforeSave()
    {
        $this->periode_name = $this->periode;
        $this->prodi_name   = $this->prodi;
    }

    /**
     * Find the target Google Drive folder ID for this Monev record.
     * Path: FOLDER_IDS[category_label] -> Periode subfolder -> Prodi subfolder
     *
     * @return string|null
     */
    protected function getMonevTargetFolderId()
    {
        $categoryFolderId = GoogleDriveReader::FOLDER_IDS[$this->category_label] ?? null;
        if (!$categoryFolderId || !$this->periode || !$this->prodi) {
            return null;
        }

        $periodeFolder = GoogleDriveReader::findSubfolderByName($categoryFolderId, $this->periode);
        if (!$periodeFolder || !isset($periodeFolder['id'])) {
            \Log::warning("Monev: Periode folder not found for '{$this->periode}' in category '{$this->category_label}'");
            return null;
        }

        $prodiFolder = GoogleDriveReader::findSubfolderByName($periodeFolder['id'], $this->prodi);
        if (!$prodiFolder || !isset($prodiFolder['id'])) {
            \Log::warning("Monev: Prodi folder not found for '{$this->prodi}' in periode '{$this->periode}'");
            return null;
        }

        return $prodiFolder['id'];
    }

    /**
     * Hapus file lama di Google Drive sebelum upload file baru
     */
    protected function deleteOldFileIfExists()
    {
        $oldFileName = $this->getOriginal('file_name');
        if (!$oldFileName) return;

        $targetFolderId = $this->getMonevTargetFolderId();
        if (!$targetFolderId) return;

        $existingFile = GoogleDriveReader::findFileByName($targetFolderId, $oldFileName);
        if ($existingFile) {
            GoogleDriveUploader::delete($existingFile['id']);
        }
    }

    /**
     * afterSave - trigger upload setelah semua relasi tersimpan
     */
    public function afterSave()
    {
        // Skip jika sedang dalam proses upload (mencegah recursive call)
        if ($this->isUploadingToGoogleDrive) {
            return;
        }

        // Gunakan deferred binding untuk memastikan file sudah attached
        if ($this->file()->withDeferred($this->sessionKey)->count() > 0) {
            $file = $this->file()->withDeferred($this->sessionKey)->first();

            if ($file && $this->category && $this->periode && $this->prodi && $this->title) {
                $this->deleteOldFileIfExists();
                $this->uploadFileToGoogleDrive($file);
            }
        }
        // Jika file sudah committed (bukan deferred)
        elseif ($this->file && $this->category && $this->periode && $this->prodi && $this->title) {
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

            // Cari folder Prodi di dalam Periode di dalam Kategori
            $targetFolderId = $this->getMonevTargetFolderId();

            if (!$targetFolderId) {
                \Log::error('Monev: Could not find target folder for ' . $this->category_label . ' -> ' . $this->periode . ' -> ' . $this->prodi);
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

                // Bust cache for this category's nested structure
                $categoryFolderId = GoogleDriveReader::FOLDER_IDS[$this->category_label] ?? null;
                if ($categoryFolderId) {
                    \Cache::forget('gdrive_structure_' . md5($categoryFolderId));
                    \Cache::forget('gdrive_structure_' . md5($categoryFolderId) . '_shallow');
                }

                $this->deleteLocalFileRelation($file, $filePath);
            } else {
                \Log::warning('Upload response missing fileId', ['response' => $result]);
            }
        } catch (\Exception $e) {
            $this->isUploadingToGoogleDrive = false;
            \Log::error('Google Drive upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Get the available category options.
     *
     * @return array
     */
    public static function getCategoryOptions()
    {
        return [
            1 => 'Beban Belajar Mahasiswa',
            2 => 'Monev Dosen',
            3 => 'Monev Kehadiran Mahasiswa',
            4 => 'Monev Materi dengan RPS',
            5 => 'Monev Nilai',
            6 => 'Monev UTS UAS -- RPS',
        ];
    }

    public function getCategoryLabelAttribute()
    {
        $options = self::getCategoryOptions();
        return isset($options[$this->category]) ? $options[$this->category] : 'Kategori Tidak Diketahui';
    }

    public function getDisplayCategoryAttribute()
    {
        $options = self::getCategoryOptions();
        return isset($options[$this->category]) ? $options[$this->category] : 'Kategori Tidak Diketahui';
    }

    public function getDisplayPeriodeAttribute()
    {
        return $this->periode_name ?: $this->periode;
    }

    public function getDisplayProdiAttribute()
    {
        return $this->prodi_name ?: $this->prodi;
    }

    /**
     * Hapus relasi file lokal setelah upload sukses
     */
    protected function deleteLocalFileRelation($file, $filePath)
    {
        try {
            // Hapus file fisik dari storage
            if (file_exists($filePath)) {
                @unlink($filePath);
                // \Log::info('Local file deleted', ['filePath' => $filePath]);
            }

            // Hapus relasi file di database
            $file->delete();
            // \Log::info('File relation deleted from database');
        } catch (\Exception $e) {
            \Log::error('Failed to delete local file', [
                'error' => $e->getMessage(),
                'filePath' => $filePath
            ]);
        }
    }

    public function afterDelete()
    {
        if ($this->file_name) {
            try {
                $targetFolderId = $this->getMonevTargetFolderId();

                if ($targetFolderId) {
                    $existingFile = GoogleDriveReader::findFileByName($targetFolderId, $this->file_name);
                    if ($existingFile) {
                        GoogleDriveUploader::delete($existingFile['id']);
                    }
                }

                // Bust cache for this category's nested structure
                $categoryFolderId = GoogleDriveReader::FOLDER_IDS[$this->category_label] ?? null;
                if ($categoryFolderId) {
                    \Cache::forget('gdrive_structure_' . md5($categoryFolderId));
                    \Cache::forget('gdrive_structure_' . md5($categoryFolderId) . '_shallow');
                }
            } catch (\Exception $e) {
                \Log::error('Google Drive delete failed: ' . $e->getMessage());
            }
        }
    }
}
