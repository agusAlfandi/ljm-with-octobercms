<?php
namespace Agus\Tabler\Traits;

use Agus\Tabler\Classes\GoogleDriveReader;

trait SpmiOptions
{
    /**
     * Get hardcoded Periode options
     * @return array
     */
    public function getPeriodeOptions()
    {
        return [
            'Periode 2021-2022' => 'Periode 2021-2022',
            'Periode 2022-2023' => 'Periode 2022-2023',
            'Periode 2023-2024' => 'Periode 2023-2024',
            'Periode 2024-2025' => 'Periode 2024-2025',
        ];
    }

    /**
     * Get hardcoded Prodi options based on the provided image
     * @return array
     */
    public function getProdiOptions()
    {
        return [
            'D3 Kebidanan' => 'D3 Kebidanan',
            'D3 Keperawatan' => 'D3 Keperawatan',
            'D3 Manajemen Informatika' => 'D3 Manajemen Informatika',
            'D3 Rekam Medik dan Informasi Kesehatan' => 'D3 Rekam Medik dan Informasi Kesehatan',
            'D3 Teknik Komputer' => 'D3 Teknik Komputer',
            'D4 Kimia Industri' => 'D4 Kimia Industri',
            'D4 Teknologi Rekayasa Komputer' => 'D4 Teknologi Rekayasa Komputer',
            'D4 Teknologi Rekayasa Perangkat Lunak' => 'D4 Teknologi Rekayasa Perangkat Lunak',
            'D4 TLM' => 'D4 TLM',
            'Pendidikan Profesi Ners' => 'Pendidikan Profesi Ners',
            'S1 Agribisnis' => 'S1 Agribisnis',
            'S1 Akuntansi' => 'S1 Akuntansi',
            'S1 ARS' => 'S1 ARS',
            'S1 Bahasa Inggris' => 'S1 Bahasa Inggris',
            'S1 Farmasi' => 'S1 Farmasi',
            'S1 Hukum' => 'S1 Hukum',
            'S1 Ilmu Komunikasi' => 'S1 Ilmu Komunikasi',
            'S1 Kebidanan' => 'S1 Kebidanan',
            'S1 Keperawatan' => 'S1 Keperawatan',
            'S1 Manajemen' => 'S1 Manajemen',
            'S1 Pendidikan Bahasa Inggris' => 'S1 Pendidikan Bahasa Inggris',
            'S1 PGSD' => 'S1 PGSD',
            'S1 Sistem Informasi' => 'S1 Sistem Informasi',
            'S1 Teknik Industri' => 'S1 Teknik Industri',
            'S1 Teknik Informatika' => 'S1 Teknik Informatika',
            'SK Profesi Kebidanan' => 'SK Profesi Kebidanan',
        ];
    }

    /**
     * Find the target Folder ID in Google Drive based on Periode and Prodi names
     * @param string $rootKey Key in GoogleDriveReader::FOLDER_IDS (e.g. 'ROOT_AMI')
     * @return string|null
     */
    protected function getTargetFolderId($rootKey)
    {
        $rootId = GoogleDriveReader::FOLDER_IDS[$rootKey] ?? null;
        if (!$rootId || !$this->periode || !$this->prodi) {
            return null;
        }

        // 1. Find the Periode folder ID inside Root
        $periodeFolder = GoogleDriveReader::findSubfolderByName($rootId, $this->periode);
        if (!$periodeFolder || !isset($periodeFolder['id'])) {
            \Log::warning("SPMI: Periode folder not found for '{$this->periode}' in root '{$rootKey}'");
            return null;
        }

        $periodeId = $periodeFolder['id'];

        // 2. Find the Prodi folder ID inside Periode
        $prodiFolder = GoogleDriveReader::findSubfolderByName($periodeId, $this->prodi);
        if (!$prodiFolder || !isset($prodiFolder['id'])) {
            \Log::warning("SPMI: Prodi folder not found for '{$this->prodi}' in periode '{$this->periode}'");
            return null;
        }

        return $prodiFolder['id'];
    }
}
