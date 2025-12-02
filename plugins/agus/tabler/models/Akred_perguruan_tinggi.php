<?php namespace Agus\Tabler\Models;

use Model;
use Agus\Tabler\Classes\GoogleDriveUploader;

/**
 * Model
 */
class Akred_perguruan_tinggi extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_akred_perguruan_tinggi';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'title' => 'required',
        'file' => 'required'
    ];

    /**
     *@var array
     */
    protected $fillable = [
        'title',
        'file'
    ];

        /**
         * @var array Attributes to attach files.
         */
    public $attachOne = [
        'file' => 'System\Models\File'
    ];

    /**
     * Hapus file lama di Google Drive sebelum upload file baru
     */
    protected function deleteOldFileIfExists()
    {
        // Ambil data lama dari database sebelum update
        $oldFileName = $this->getOriginal('file_name');

        // \Log::info('Checking for old file to delete', [
        //     'old_file_name' => $oldFileName,
        //     'new_file_exists' => !empty($this->file)
        // ]);

        if ($oldFileName) {
            // Hapus file lama di Google Drive
            $category = 'Akreditasi Perguruan Tinggi';
            $categoryKey = \Agus\Tabler\Classes\GoogleDriveUploader::normalizeCategoryName($category);
            $folderId = \Agus\Tabler\Classes\GoogleDriveReader::FOLDER_IDS[$categoryKey] ?? null;

            if ($folderId) {
                $existingFile = \Agus\Tabler\Classes\GoogleDriveReader::findFileByName($folderId, $oldFileName);

                if ($existingFile) {
                    \Agus\Tabler\Classes\GoogleDriveUploader::delete($existingFile['id']);
                    // \Log::info('Old file deleted from Google Drive before update', [
                    //     'fileId' => $existingFile['id'],
                    //     'fileName' => $oldFileName
                    // ]);
                } else {
                    \Log::warning('Old file not found in Google Drive', [
                        'fileName' => $oldFileName,
                        'folderId' => $folderId
                    ]);
                }
            }
        }
    }

    public function afterSave()
    {

        // Gunakan deferred binding untuk memastikan file sudah attached
        if ($this->file()->withDeferred($this->sessionKey)->count() > 0) {
            $file = $this->file()->withDeferred($this->sessionKey)->first();

            if ($file) {
                // Jika ada file baru, hapus file lama di Google Drive dulu
                $this->deleteOldFileIfExists();
                // Upload file baru
                $this->uploadFileToGoogleDrive($file);
            }
        } elseif ($this->file) {
            \Log::info('afterSave Rekognisi (committed)', [
                'id' => $this->id,
                'file_id' => $this->file->id,
                'old_file_name' => $this->getOriginal('file_name')
            ]);
            // Jika ada file baru, hapus file lama di Google Drive dulu
            $this->deleteOldFileIfExists();
            // Upload file baru
            $this->uploadFileToGoogleDrive($this->file);
        } else {
            \Log::warning('afterSave Rekognisi - No file found', [
                'id' => $this->id
            ]);
        }
    }

    /**
     * Hapus file relasi dari storage setelah upload sukses ke Google Drive
     * (dipanggil dari uploadFileToGoogleDrive)
     */
    protected function deleteLocalFileRelation($file)
    {
        // Hapus file fisik dari storage
        $filePath = $file->getLocalPath();
        if (file_exists($filePath)) {
            @unlink($filePath);
            // \Log::info('Local file deleted after upload', ['file_path' => $filePath]);
        }
        // Hapus relasi file di OctoberCMS
        $file->delete();
        // \Log::info('File relation deleted after upload', ['file_id' => $file->id]);
    }

    /**
     * Helper method untuk upload file ke Google Drive
     */
    protected function uploadFileToGoogleDrive($file)
    {
        try {
            $filePath = $file->getLocalPath();
            $fileName = $file->file_name;
            $category = 'Akreditasi Perguruan Tinggi';

            $result = GoogleDriveUploader::uploadToCategory($filePath, $category, $fileName);

            if (isset($result['fileId'])) {

                // Simpan nama file yang diupload ke kolom file_name
                $uploadedFileName = $result['fileName'] ?? $fileName;
                if (!preg_match('/\.pdf$/i', $uploadedFileName)) {
                    $uploadedFileName .= '.pdf';
                }



                $this->file_name = $uploadedFileName;

                // Disable validation temporarily untuk save file_name
                $this->rules = [];
                $this->save();

                 // Hapus file lokal dan relasi setelah upload sukses
                $this->deleteLocalFileRelation($file);
            } else {
                \Log::warning('Upload Rekognisi: response missing fileId', [
                    'response' => $result
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Google Drive upload failed (Rekognisi)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function beforeDelete()
    {
        // Tidak perlu, gunakan kolom uploaded_filename
    }

    public function afterDelete()
    {
        // Saat record dihapus, hapus juga file dari Google Drive
        if ($this->file_name) {
            try {
                // Gunakan normalizeCategoryName agar key konsisten dengan FOLDER_IDS
                $category = 'Akreditasi Perguruan Tinggi';
                $categoryKey = \Agus\Tabler\Classes\GoogleDriveUploader::normalizeCategoryName($category);

                // \Log::info('afterDelete SOP SPMI - Debug', [
                //     'original_category' => $category,
                //     'normalized_category' => $categoryKey,
                //     'file_name' => $this->file_name,
                //     'available_keys' => array_keys(\Agus\Tabler\Classes\GoogleDriveReader::FOLDER_IDS)
                // ]);

                $folderId = \Agus\Tabler\Classes\GoogleDriveReader::FOLDER_IDS[$categoryKey] ?? null;

                // \Log::info('afterDelete Informasi Publik - Folder lookup', [
                //     'categoryKey' => $categoryKey,
                //     'folderId' => $folderId
                // ]);

                if ($folderId) {
                    $fileName = $this->file_name;

                    // \Log::info('afterDelete Informasi Publik - Searching file', [
                    //     'folderId' => $folderId,
                    //     'fileName' => $fileName
                    // ]);

                    $existingFile = \Agus\Tabler\Classes\GoogleDriveReader::findFileByName($folderId, $fileName);

                    // \Log::info('afterDelete Informasi Publik - File search result', [
                    //     'existingFile' => $existingFile
                    // ]);

                    if ($existingFile) {
                        \Agus\Tabler\Classes\GoogleDriveUploader::delete($existingFile['id']);
                        // \Log::info('File deleted from Google Drive', [
                        //     'fileId' => $existingFile['id'],
                        //     'fileName' => $fileName
                        // ]);
                    } else {
                        \Log::warning('File not found in Google Drive folder', [
                            'folderId' => $folderId,
                            'fileName' => $fileName
                        ]);
                    }
                } else {
                    \Log::error('Folder ID not found for category', [
                        'categoryKey' => $categoryKey
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Google Drive delete failed (Informasi Publik): ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            \Log::warning('afterDelete called but no file_name found', [
                'model_id' => $this->id
            ]);
        }
    }

}
