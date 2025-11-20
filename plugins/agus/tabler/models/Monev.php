<?php namespace Agus\Tabler\Models;

use Model;
use Agus\Tabler\Classes\GoogleDriveUploader;
use Agus\Tabler\Classes\GoogleDriveReader;

/**
 * Model
 */
class Monev extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_monev';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    /**
     * @var array Attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'category',
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
            // Hapus file lama di Google Drive menggunakan category dari model
            $categoryName = GoogleDriveUploader::normalizeCategoryName($this->category_label);
            $folderId = GoogleDriveReader::FOLDER_IDS[$categoryName] ?? null;

            if ($folderId) {
                $existingFile = GoogleDriveReader::findFileByName($folderId, $oldFileName);

                if ($existingFile) {
                    GoogleDriveUploader::delete($existingFile['id']);
                    // \Log::info('Old file deleted from Google Drive before update', [
                    //     'fileId' => $existingFile['id'],
                    //     'fileName' => $oldFileName,
                    //     'category' => $this->category_label
                    // ]);
                } else {
                    // \Log::warning('Old file not found in Google Drive', [
                    //     'fileName' => $oldFileName,
                    //     'folderId' => $folderId,
                    //     'category' => $this->category_label
                    // ]);
                }
            }
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

            // \Log::info('afterSave with deferred file', [
            //     'id' => $this->id,
            //     'title' => $this->title,
            //     'category' => $this->category,
            //     'file_id' => $file ? $file->id : null,
            //     'file_path' => $file ? $file->getPath() : null
            // ]);

            if ($file && $this->category && $this->title) {
                // Hapus file lama jika ada sebelum upload file baru
                $this->deleteOldFileIfExists();
                $this->uploadFileToGoogleDrive($file);
            }
        }
        // Jika file sudah committed (bukan deferred)
        elseif ($this->file && $this->category && $this->title) {
            // \Log::info('afterSave with committed file', [
            //     'id' => $this->id,
            //     'title' => $this->title,
            //     'category' => $this->category,
            //     'file_id' => $this->file->id
            // ]);

            // Hapus file lama jika ada sebelum upload file baru
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
            $fileName = $file->file_name; // Gunakan nama file asli, bukan title

            // \Log::info('Uploading file to Google Drive', [
            //     'filePath' => $filePath,
            //     'fileName' => $fileName,
            //     'title' => $this->title,
            //     'category' => $this->category
            // ]);

            // Upload dengan nama file asli
            $result = GoogleDriveUploader::uploadToCategory($filePath, $this->category_label, $fileName);

            if (isset($result['fileId'])) {
                // Set flag untuk mencegah recursive call
                $this->isUploadingToGoogleDrive = true;

                // Simpan nama file yang diupload ke database
                $uploadedFileName = $result['fileName'] ?? $fileName;
                if (!preg_match('/\.pdf$/i', $uploadedFileName)) {
                    $uploadedFileName .= '.pdf';
                }
                $this->file_name = $uploadedFileName;
                $this->save();

                // Reset flag
                $this->isUploadingToGoogleDrive = false;

                // \Log::info('File successfully uploaded to Google Drive', [
                //     'fileId' => $result['fileId'],
                //     'fileName' => $uploadedFileName,
                //     'category' => $this->category,
                //     'categoryName' => $this->category_label,
                //     'folderId' => $result['folderId'] ?? null,
                //     'folderName' => $result['folderName'] ?? null
                // ]);

                // Hapus file lokal dan relasi setelah upload sukses
                $this->deleteLocalFileRelation($file, $filePath);
            } else {
                \Log::warning('Upload response missing fileId', [
                    'response' => $result
                ]);
            }
        } catch (\Exception $e) {
            // Reset flag jika terjadi error
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

    /**
     * Hapus relasi file lokal setelah upload sukses
     */
    protected function deleteLocalFileRelation($file, $filePath)
    {
        try {
            // Hapus file fisik dari storage
            if (file_exists($filePath)) {
                @unlink($filePath);
                \Log::info('Local file deleted', ['filePath' => $filePath]);
            }

            // Hapus relasi file di database
            $file->delete();
            \Log::info('File relation deleted from database');
        } catch (\Exception $e) {
            \Log::error('Failed to delete local file', [
                'error' => $e->getMessage(),
                'filePath' => $filePath
            ]);
        }
    }

    public function afterDelete()
    {
        // Saat record dihapus, hapus juga file dari Google Drive menggunakan file_name dari database
        if ($this->file_name && $this->category) {
            try {
                // Cari file di Google Drive berdasarkan nama dari database
                $categoryName = GoogleDriveUploader::normalizeCategoryName($this->category_label);
                $folderId = GoogleDriveReader::FOLDER_IDS[$categoryName] ?? null;

                if ($folderId) {
                    $existingFile = GoogleDriveReader::findFileByName($folderId, $this->file_name);

                    if ($existingFile) {
                        GoogleDriveUploader::delete($existingFile['id']);
                        // \Log::info('File deleted from Google Drive', [
                        //     'fileId' => $existingFile['id'],
                        //     'fileName' => $this->file_name,
                        //     'category' => $this->category_label
                        // ]);
                    } else {
                        \Log::warning('File not found in Google Drive for deletion', [
                            'fileName' => $this->file_name,
                            'category' => $this->category_label
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Google Drive delete failed: ' . $e->getMessage());
            }
        }
    }
}
