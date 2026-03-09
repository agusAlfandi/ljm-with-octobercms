<?php namespace Agus\Tabler\Models;

use Model;
use Agus\Tabler\Classes\GoogleDriveReader;
use Agus\Tabler\Classes\GoogleDriveUploader;

/**
 * Model
 */
class Rtm extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \Agus\Tabler\Traits\SpmiOptions;  // reuse helper from AMI model

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_rtm';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'title'   => 'required',
        'level'   => 'required',
        'periode' => 'required',
        'file'    => 'required'
    ];

    /**
     * Conditionally require prodi for non-Universitas levels.
     */
    public function beforeValidate()
    {
        if ($this->level !== 'Universitas') {
            $this->rules['prodi'] = 'required';
        }
    }

    /**
     * @var array
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
     * Override getProdiOptions to filter by fakultas when level = Fakultas
     */
    public function getProdiOptions()
    {
        if ($this->level === 'Universitas') {
            return [];
        }

        $prodiByFakultas = [
            'Fakultas Ilmu Komputer' => [
                'D3 Manajemen Informatika'              => 'D3 Manajemen Informatika',
                'D3 Teknik Komputer'                    => 'D3 Teknik Komputer',
                'D4 Teknologi Rekayasa Perangkat Lunak' => 'D4 Teknologi Rekayasa Perangkat Lunak',
                'S1 Sistem Informasi'                   => 'S1 Sistem Informasi',
                'S1 Teknik Informatika'                 => 'S1 Teknik Informatika',
            ],
            'Fakultas Ilmu Kesehatan' => [
                'D3 Kebidanan'                           => 'D3 Kebidanan',
                'D3 Keperawatan'                         => 'D3 Keperawatan',
                'D3 Rekam Medik dan Informasi Kesehatan' => 'D3 Rekam Medik dan Informasi Kesehatan',
                'D4 TLM'                                 => 'D4 TLM',
                'S1 ARS'                                 => 'S1 ARS (Administrasi Rumah Sakit)',
                'S1 Farmasi'                             => 'S1 Farmasi',
                'S1 Kebidanan'                           => 'S1 Kebidanan',
                'S1 Keperawatan'                         => 'S1 Keperawatan',
                'Pendidikan Profesi Ners'                => 'Pendidikan Profesi Ners',
                'SK Profesi Kebidanan'                   => 'SK Profesi Kebidanan',
            ],
            'Fakultas Hukum dan Bisnis' => [
                'S1 Akuntansi'       => 'S1 Akuntansi',
                'S1 Bahasa Inggris'  => 'S1 Bahasa Inggris',
                'S1 Hukum'           => 'S1 Hukum',
                'S1 Ilmu Komunikasi' => 'S1 Ilmu Komunikasi',
                'S1 Manajemen'       => 'S1 Manajemen',
            ],
            'Fakultas Sains dan Teknologi' => [
                'D4 Kimia Industri'            => 'D4 Kimia Industri',
                'D4 Teknologi Rekayasa Pangan' => 'D4 Teknologi Rekayasa Pangan',
                'S1 Agribisnis'                => 'S1 Agribisnis',
                'S1 Teknik Industri'           => 'S1 Teknik Industri',
            ],
            'Fakultas Keguruan dan Ilmu Pendidikan' => [
                'S1 PGSD'                     => 'S1 PGSD',
                'S1 Pendidikan Bahasa Inggris' => 'S1 Pendidikan Bahasa Inggris',
            ],
        ];

        if ($this->level === 'Fakultas' && $this->fakultas && isset($prodiByFakultas[$this->fakultas])) {
            return $prodiByFakultas[$this->fakultas];
        }

        $all = [];
        foreach ($prodiByFakultas as $list) {
            $all = array_merge($all, $list);
        }
        ksort($all);
        return $all;
    }

    /**
     * Map fakultas name to its Google Drive folder ID
     */
    protected function getFakultasFolderId($fakultasName)
    {
        $map = [
            'Fakultas Hukum dan Bisnis'             => GoogleDriveReader::FOLDER_IDS['RTM_FAK_HUKUM_BISNIS'],
            'Fakultas Ilmu Kesehatan'               => GoogleDriveReader::FOLDER_IDS['RTM_FAK_ILMU_KESEHATAN'],
            'Fakultas Ilmu Komputer'                => GoogleDriveReader::FOLDER_IDS['RTM_FAK_ILMU_KOMPUTER'],
            'Fakultas Kedokteran'                   => GoogleDriveReader::FOLDER_IDS['RTM_FAK_KEDOKTERAN'],
            'Fakultas Keguruan dan Ilmu Pendidikan' => GoogleDriveReader::FOLDER_IDS['RTM_FAK_KEGURUAN_ILMU_PEND'],
            'Fakultas Sains dan Teknologi'          => GoogleDriveReader::FOLDER_IDS['RTM_FAK_SAINS_TEKNOLOGI'],
        ];

        return $map[$fakultasName] ?? null;
    }

    /**
     * Override getTargetFolderId to navigate through RTM level folder
     * Universitas path       : level folder → Periode (no Prodi subfolder)
     * Prodi path             : level folder → Periode → Prodi
     * Fakultas path          : level folder → Fakultas → Periode → Prodi
     */
    protected function getTargetFolderId($rootKey)
    {
        if (!$this->periode || !$this->level) {
            return null;
        }

        // --- Universitas: files go directly inside the Periode folder (no Prodi subfolder) ---
        if ($this->level === 'Universitas') {
            $levelId = GoogleDriveReader::FOLDER_IDS['RTM_LEVEL_UNIVERSITAS'] ?? null;
            if (!$levelId) return null;
            $periodeFolder = GoogleDriveReader::createOrFindSubfolder($levelId, $this->periode);
            if (!$periodeFolder || !isset($periodeFolder['id'])) {
                \Log::warning("RTM: Gagal membuat/menemukan Folder Periode '{$this->periode}' di level Universitas");
                return null;
            }
            return $periodeFolder['id'];
        }

        if (!$this->prodi) {
            return null;
        }

        // --- Fakultas: 3-level path ---
        if ($this->level === 'Fakultas') {
            if (!$this->fakultas) {
                \Log::warning('RTM: level Fakultas dipilih tapi fakultas kosong');
                return null;
            }

            $fakultasId = $this->getFakultasFolderId($this->fakultas);
            if (!$fakultasId) {
                \Log::warning("RTM: Folder Fakultas '{$this->fakultas}' tidak dikenali");
                return null;
            }

            $periodeFolder = GoogleDriveReader::createOrFindSubfolder($fakultasId, $this->periode);
            if (!$periodeFolder || !isset($periodeFolder['id'])) {
                \Log::warning("RTM: Gagal membuat/menemukan Folder Periode '{$this->periode}' di fakultas '{$this->fakultas}'");
                return null;
            }

            $prodiFolder = GoogleDriveReader::createOrFindSubfolder($periodeFolder['id'], $this->prodi);
            if (!$prodiFolder || !isset($prodiFolder['id'])) {
                \Log::warning("RTM: Gagal membuat/menemukan Folder Prodi '{$this->prodi}' di periode '{$this->periode}'");
                return null;
            }

            return $prodiFolder['id'];
        }

        // --- Prodi: 2-level path ---
        $levelId = GoogleDriveReader::FOLDER_IDS['RTM_LEVEL_PRODI'] ?? null;
        if (!$levelId) {
            \Log::warning('RTM: level tidak valid atau folder ID tidak ditemukan: ' . $this->level);
            return null;
        }

        $periodeFolder = GoogleDriveReader::createOrFindSubfolder($levelId, $this->periode);
        if (!$periodeFolder || !isset($periodeFolder['id'])) {
            \Log::warning("RTM: Gagal membuat/menemukan Folder Periode '{$this->periode}' di level '{$this->level}'");
            return null;
        }

        $prodiFolder = GoogleDriveReader::createOrFindSubfolder($periodeFolder['id'], $this->prodi);
        if (!$prodiFolder || !isset($prodiFolder['id'])) {
            \Log::warning("RTM: Gagal membuat/menemukan Folder Prodi '{$this->prodi}' di dalam periode '{$this->periode}'");
            return null;
        }

        return $prodiFolder['id'];
    }

    public function beforeSave()
    {
        // keep copies of the selected names
        $this->periode_name = $this->periode;
        $this->prodi_name   = $this->level === 'Universitas' ? null : $this->prodi;
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
        // Skip when already uploading (prevent recursion)
        if ($this->isUploadingToGoogleDrive) {
            return;
        }

        $hasRequiredFields = $this->periode && $this->title &&
            ($this->level === 'Universitas' || $this->prodi);

        // handle deferred binding or immediate file
        if ($this->file()->withDeferred($this->sessionKey)->count() > 0) {
            $file = $this->file()->withDeferred($this->sessionKey)->first();
            if ($file && $hasRequiredFields) {
                $this->deleteOldFileIfExists();
                $this->uploadFileToGoogleDrive($file);
            }
        } elseif ($this->file && $hasRequiredFields) {
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

            // find destination folder under ROOT_RTM using periode/prodi
            $targetFolderId = $this->getTargetFolderId('ROOT_RTM');
            if (!$targetFolderId) {
                \Log::error('RTM: unable to resolve target folder for ' . $this->periode . '/' . $this->prodi);
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

                // clear cache so list updates immediately
                $this->flushRtmCache();

                $this->deleteLocalFileRelation($file, $filePath);
            } else {
                \Log::warning('Upload response missing fileId', ['response' => $result]);
            }
        } catch (\Exception $e) {
            $this->isUploadingToGoogleDrive = false;
            \Log::error('Google Drive upload failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    /**
     * Hapus file lama di Google Drive sebelum upload file baru
     */
    protected function deleteOldFileIfExists()
    {
        $oldFileName = $this->getOriginal('file_name');
        $oldFolderId = $this->getTargetFolderId('ROOT_RTM');

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

    /**
     * Flush Google Drive cache for all RTM level folders
     */
    protected function flushRtmCache()
    {
        $keys = ['ROOT_RTM', 'RTM_LEVEL_PRODI', 'RTM_LEVEL_FAKULTAS', 'RTM_LEVEL_UNIVERSITAS'];
        foreach ($keys as $key) {
            if (!empty(GoogleDriveReader::FOLDER_IDS[$key])) {
                $hash = md5(GoogleDriveReader::FOLDER_IDS[$key]);
                \Cache::forget('gdrive_structure_' . $hash);
                \Cache::forget('gdrive_structure_' . $hash . '_shallow');
            }
        }
    }

    public function afterDelete()
    {
        if ($this->file_name) {
            try {
                $targetFolderId = $this->getTargetFolderId('ROOT_RTM');
                if ($targetFolderId) {
                    $existingFile = GoogleDriveReader::findFileByName($targetFolderId, $this->file_name);
                    if ($existingFile) {
                        GoogleDriveUploader::delete($existingFile['id']);
                    }
                }
                $this->flushRtmCache();
            } catch (\Exception $e) {
                \Log::error('Google Drive delete failed: ' . $e->getMessage());
            }
        }
    }

}
