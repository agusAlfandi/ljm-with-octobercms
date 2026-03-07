<?php namespace Agus\Tabler\Models;

use Model;
use Agus\Tabler\Classes\GoogleDriveReader;
use Agus\Tabler\Classes\GoogleDriveUploader;
use Agus\Tabler\Traits\SpmiOptions;

/**
 * Model
 */
class Ami_documents extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use SpmiOptions;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_ami';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'title' => 'required',
        'level' => 'required',
        'periode' => 'required',
        'prodi' => 'required',
        'file' => 'required'
    ];

    /**
     *@var array
     */
    protected $fillable = [
        'title',
        'level',
        'fakultas',
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
     * Get display periode attribute (accessor)
     */
    public function getDisplayPeriodeAttribute()
    {
        return $this->periode_name ?: $this->periode;
    }

    /**
     * Get display prodi attribute (accessor)
     */
    public function getDisplayProdiAttribute()
    {
        return $this->prodi_name ?: $this->prodi;
    }

     /**
     * @var bool Flag to prevent recursive afterSave calls
     */
    protected $isUploadingToGoogleDrive = false;


    /**
     * Get level options (Prodi, Fakultas, Universitas)
     */
    public function getLevelOptions()
    {
        return [
            'Prodi'       => 'Prodi',
            'Fakultas'    => 'Fakultas',
            'Universitas' => 'Universitas',
        ];
    }

    /**
     * Get Fakultas options — only shown when level = Fakultas
     */
    public function getFakultasOptions()
    {
        if ($this->level !== 'Fakultas') {
            return [];
        }

        return [
            'Fakultas Hukum dan Bisnis'              => 'Fakultas Hukum dan Bisnis',
            'Fakultas Ilmu Kesehatan'                => 'Fakultas Ilmu Kesehatan',
            'Fakultas Ilmu Komputer'                 => 'Fakultas Ilmu Komputer',
            'Fakultas Kedokteran'                    => 'Fakultas Kedokteran',
            'Fakultas Keguruan dan Ilmu Pendidikan'  => 'Fakultas Keguruan dan Ilmu Pendidikan',
            'Fakultas Sains dan Teknologi'           => 'Fakultas Sains dan Teknologi',
        ];
    }

    /**
     * Map fakultas name to its Google Drive folder ID
     */
    protected function getFakultasFolderId($fakultasName)
    {
        $map = [
            'Fakultas Hukum dan Bisnis'             => GoogleDriveReader::FOLDER_IDS['AMI_FAK_HUKUM_BISNIS'],
            'Fakultas Ilmu Kesehatan'               => GoogleDriveReader::FOLDER_IDS['AMI_FAK_ILMU_KESEHATAN'],
            'Fakultas Ilmu Komputer'                => GoogleDriveReader::FOLDER_IDS['AMI_FAK_ILMU_KOMPUTER'],
            'Fakultas Kedokteran'                   => GoogleDriveReader::FOLDER_IDS['AMI_FAK_KEDOKTERAN'],
            'Fakultas Keguruan dan Ilmu Pendidikan' => GoogleDriveReader::FOLDER_IDS['AMI_FAK_KEGURUAN_ILMU_PEND'],
            'Fakultas Sains dan Teknologi'          => GoogleDriveReader::FOLDER_IDS['AMI_FAK_SAINS_TEKNOLOGI'],
        ];

        return $map[$fakultasName] ?? null;
    }

    /**
     * Override getTargetFolderId to navigate through AMI level folder
     * Prodi/Universitas path : level folder → Periode → Prodi
     * Fakultas path           : level folder → Fakultas → Periode → Prodi
     */
    protected function getTargetFolderId($rootKey)
    {
        if (!$this->periode || !$this->prodi || !$this->level) {
            return null;
        }

        // --- Fakultas: 3-level path ---
        if ($this->level === 'Fakultas') {
            if (!$this->fakultas) {
                \Log::warning('AMI: level Fakultas dipilih tapi fakultas kosong');
                return null;
            }

            $fakultasId = $this->getFakultasFolderId($this->fakultas);
            if (!$fakultasId) {
                \Log::warning("AMI: Folder Fakultas '{$this->fakultas}' tidak dikenali");
                return null;
            }

            $periodeFolder = GoogleDriveReader::createOrFindSubfolder($fakultasId, $this->periode);
            if (!$periodeFolder || !isset($periodeFolder['id'])) {
                \Log::warning("AMI: Gagal membuat/menemukan Folder Periode '{$this->periode}' di fakultas '{$this->fakultas}'");
                return null;
            }

            $prodiFolder = GoogleDriveReader::createOrFindSubfolder($periodeFolder['id'], $this->prodi);
            if (!$prodiFolder || !isset($prodiFolder['id'])) {
                \Log::warning("AMI: Gagal membuat/menemukan Folder Prodi '{$this->prodi}' di periode '{$this->periode}'");
                return null;
            }

            return $prodiFolder['id'];
        }

        // --- Prodi / Universitas: 2-level path ---
        $levelFolderIds = [
            'Prodi'       => GoogleDriveReader::FOLDER_IDS['AMI_LEVEL_PRODI'],
            'Universitas' => GoogleDriveReader::FOLDER_IDS['AMI_LEVEL_UNIVERSITAS'],
        ];

        if (!isset($levelFolderIds[$this->level])) {
            \Log::warning('AMI: level tidak valid: ' . $this->level);
            return null;
        }

        $levelId = $levelFolderIds[$this->level];

        $periodeFolder = GoogleDriveReader::createOrFindSubfolder($levelId, $this->periode);
        if (!$periodeFolder || !isset($periodeFolder['id'])) {
            \Log::warning("AMI: Gagal membuat/menemukan Folder Periode '{$this->periode}' di level '{$this->level}'");
            return null;
        }

        $prodiFolder = GoogleDriveReader::createOrFindSubfolder($periodeFolder['id'], $this->prodi);
        if (!$prodiFolder || !isset($prodiFolder['id'])) {
            \Log::warning("AMI: Gagal membuat/menemukan Folder Prodi '{$this->prodi}' di dalam periode '{$this->periode}'");
            return null;
        }

        return $prodiFolder['id'];
    }

    public function beforeSave()
    {
        // Tetapkan nama periode dan prodi dari value yang dipilih (karena key = name sekarang)
        $this->periode_name = $this->periode;
        $this->prodi_name = $this->prodi;
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

            if ($file && $this->periode && $this->prodi && $this->title) {
                // Hapus file lama jika ada sebelum upload file baru
                $this->deleteOldFileIfExists();
                $this->uploadFileToGoogleDrive($file);
            }
        }
        // Jika file sudah committed (bukan deferred)
        elseif ($this->file && $this->periode && $this->prodi && $this->title) {
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

            // Cari Folder ID Prodi secara dinamis berdasarkan nama yang dipilih di admin
            $targetFolderId = $this->getTargetFolderId('ROOT_AMI');

            if (!$targetFolderId) {
                \Log::error('Could not find target Folder ID in Google Drive for: ' . $this->periode . ' -> ' . $this->prodi);
                return;
            }

            // Upload langsung ke folder prodi yang ditemukan (ID folder)
            $result = GoogleDriveUploader::upload($filePath, $targetFolderId, $fileName);

            if (isset($result['fileId'])) {
                // Set flag untuk mencegah recursive call
                $this->isUploadingToGoogleDrive = true;

                // Simpan nama file yang diupload ke database
                $uploadedFileName = $result['fileName'] ?? $fileName;
                if (!preg_match('/\.pdf$/i', $uploadedFileName)) {
                    $uploadedFileName .= '.pdf';
                }
                $this->file_name = $uploadedFileName;
                $this->rules = [];
                $this->save();

                // Reset flag
                $this->isUploadingToGoogleDrive = false;

                // Hapus cache Google Drive supaya data baru langsung tampil
                $this->flushAmiCache();

                // Hapus relasi file lokal dan relasi setelah upload sukses
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
     * Hapus file lama di Google Drive sebelum upload file baru
     */
    protected function deleteOldFileIfExists()
    {
        // Ambil data lama dari database sebelum update
        $oldFileName = $this->getOriginal('file_name');

        // Find folder ID for old file
        $oldFolderId = $this->getTargetFolderId('ROOT_AMI');

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
            // Hapus file fisik dari storage
            if (file_exists($filePath)) {
                @unlink($filePath);
            }

            // Hapus relasi file di database
            $file->delete();
        } catch (\Exception $e) {
            \Log::error('Failed to delete local file', [
                'error' => $e->getMessage(),
                'filePath' => $filePath
            ]);
        }
    }

    /**
     * Flush Google Drive cache for all AMI level folders
     */
    protected function flushAmiCache()
    {
        $keys = ['ROOT_AMI', 'AMI_LEVEL_PRODI', 'AMI_LEVEL_FAKULTAS', 'AMI_LEVEL_UNIVERSITAS'];
        foreach ($keys as $key) {
            if (!empty(GoogleDriveReader::FOLDER_IDS[$key])) {
                \Cache::forget('gdrive_structure_' . md5(GoogleDriveReader::FOLDER_IDS[$key]));
            }
        }
    }

    public function afterDelete()
    {
        // Saat record dihapus, hapus juga file dari Google Drive menggunakan file_name dari database
        if ($this->file_name) {
            try {
                $targetFolderId = $this->getTargetFolderId('ROOT_AMI');

                if ($targetFolderId) {
                    $existingFile = GoogleDriveReader::findFileByName($targetFolderId, $this->file_name);

                    if ($existingFile) {
                        GoogleDriveUploader::delete($existingFile['id']);
                    }
                }

                // Hapus cache supaya daftar file ter-update
                $this->flushAmiCache();
            } catch (\Exception $e) {
                \Log::error('Google Drive delete failed: ' . $e->getMessage());
            }
        }
    }

}
