<?php

namespace Agus\Tabler\Components;

use Cms\Classes\ComponentBase;
use Agus\Tabler\Classes\GoogleDriveReader;

/**
 * Generic lazy-loader component for AMI and RTM Google Drive data via AJAX.
 */
class DriveLoader extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Drive Lazy Loader',
            'description' => 'Loads AMI/RTM Drive data on-demand via AJAX',
        ];
    }

    /**
     * AJAX handler – called from the frontend with POST { type: ami|rtm }
     */
    public function onGetData()
    {
        $type  = post('type');
        $files = [];

        switch ($type) {
            case 'ami':
                // Allow extended execution time: depth=2 GAS traversal over 100 folders
                // can legitimately take 60-120 seconds on first (cache-miss) load.
                @set_time_limit(0);
                $level = post('level'); // optional: 'Prodi', 'Fakultas', or 'Universitas'
                $files = $level
                    ? GoogleDriveReader::getAmiFilesByLevel($level)
                    : GoogleDriveReader::getAllAmiFiles();
                break;

            case 'ami_folder_files':
                // Lazy-load PDF files from a specific prodi folder ID.
                // Called by the frontend when a user first clicks a prodi pill.
                @set_time_limit(0);
                $folderId = trim(post('folderId') ?? '');
                // Validate: Google Drive IDs are alphanumeric + underscores/hyphens, 20-50 chars
                if ($folderId && preg_match('/^[a-zA-Z0-9_-]{20,50}$/', $folderId)) {
                    $rawFiles = GoogleDriveReader::getFilesFromFolder($folderId);
                    $pdfs = [];
                    foreach ($rawFiles as $f) {
                        if (isset($f['mimeType']) && $f['mimeType'] === 'application/pdf') {
                            $pdfs[] = [
                                'fileId'   => $f['id'],
                                'title'    => pathinfo($f['name'], PATHINFO_FILENAME),
                                'fileName' => $f['name'],
                            ];
                        }
                    }
                    return ['type' => $type, 'label' => '', 'data' => $pdfs];
                }
                return ['type' => $type, 'label' => '', 'data' => []];
            case 'rtm':
                @set_time_limit(0);
                $level = post('level');
                $files = $level
                    ? GoogleDriveReader::getRtmFilesByLevel($level)
                    : GoogleDriveReader::getAllRtmFiles();
                break;
            case 'monev_beban':
                $files = GoogleDriveReader::getAllMonevBebanBelajarFiles();
                break;
            case 'monev_dosen':
                $files = GoogleDriveReader::getAllMonevDosenFiles();
                break;
            case 'monev_kehadiran':
                $files = GoogleDriveReader::getAllMonevKehadiranFiles();
                break;
            case 'monev_materi':
                $files = GoogleDriveReader::getAllMonevMateriRpsFiles();
                break;
            case 'monev_nilai':
                $files = GoogleDriveReader::getAllMonevNilaiFiles();
                break;
            case 'monev_uts':
                $files = GoogleDriveReader::getAllMonevUtsUasFiles();
                break;
        }

        $labels = [
            'ami'             => 'Audit Mutu Internal',
            'rtm'             => 'Rapat Tinjauan Manajemen',
            'monev_beban'     => 'Beban Belajar Mahasiswa',
            'monev_dosen'     => 'Monev Dosen',
            'monev_kehadiran' => 'Monev Kehadiran Mahasiswa',
            'monev_materi'    => 'Monev Materi dengan RPS',
            'monev_nilai'     => 'Monev Nilai',
            'monev_uts'       => 'Monev UTS UAS -- RPS',
        ];

        return [
            'type'  => $type,
            'label' => $labels[$type] ?? '',
            'data'  => $files,
        ];
    }
}
