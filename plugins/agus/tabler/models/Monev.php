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
     * afterSave - trigger upload setelah semua relasi tersimpan
     */
    public function afterSave()
    {
        // Gunakan deferred binding untuk memastikan file sudah attached
        if ($this->file()->withDeferred($this->sessionKey)->count() > 0) {
            $file = $this->file()->withDeferred($this->sessionKey)->first();

            \Log::info('afterSave with deferred file', [
                'id' => $this->id,
                'title' => $this->title,
                'category' => $this->category,
                'file_id' => $file ? $file->id : null,
                'file_path' => $file ? $file->getPath() : null
            ]);

            if ($file && $this->category && $this->title) {
                $this->uploadFileToGoogleDrive($file);
            }
        }
        // Jika file sudah committed (bukan deferred)
        elseif ($this->file && $this->category && $this->title) {
            \Log::info('afterSave with committed file', [
                'id' => $this->id,
                'title' => $this->title,
                'category' => $this->category,
                'file_id' => $this->file->id
            ]);

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

            \Log::info('Uploading file to Google Drive', [
                'filePath' => $filePath,
                'title' => $this->title,
                'category' => $this->category
            ]);

            $result = GoogleDriveUploader::uploadToCategory($filePath, $this->category, $this->title);

            if (isset($result['fileId'])) {
                \Log::info('File successfully uploaded to Google Drive', [
                    'fileId' => $result['fileId'],
                    'fileName' => $result['fileName'] ?? $this->title,
                    'category' => $this->category,
                    'categoryName' => $this->category_label,
                    'folderId' => $result['folderId'] ?? null,
                    'folderName' => $result['folderName'] ?? null
                ]);
                // // Hapus file lokal setelah upload sukses
                // if (file_exists($filePath)) {
                //     @unlink($filePath);
                // }
                // // Hapus relasi file di OctoberCMS
                // $file->delete();
            } else {
                \Log::warning('Upload response missing fileId', [
                    'response' => $result
                ]);
            }
        } catch (\Exception $e) {
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

    public function afterDelete()
    {
        // Saat record dihapus, hapus juga file dari Google Drive
        if ($this->file && $this->category && $this->title) {
            try {
                // Cari file di Google Drive berdasarkan nama
                $categoryName = GoogleDriveUploader::normalizeCategoryName($this->category);
                $folderId = GoogleDriveReader::FOLDER_IDS[$categoryName] ?? null;

                if ($folderId) {
                    $fileName = $this->title;
                    if (!preg_match('/\.pdf$/i', $fileName)) {
                        $fileName .= '.pdf';
                    }

                    $existingFile = GoogleDriveReader::findFileByName($folderId, $fileName);

                    if ($existingFile) {
                        GoogleDriveUploader::delete($existingFile['id']);
                        \Log::info('File deleted from Google Drive', [
                            'fileId' => $existingFile['id'],
                            'fileName' => $fileName
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Google Drive delete failed: ' . $e->getMessage());
            }
        }
    }
}
