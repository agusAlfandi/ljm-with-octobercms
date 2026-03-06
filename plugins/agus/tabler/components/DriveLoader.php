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
                $files = GoogleDriveReader::getAllAmiFiles();
                break;
            case 'rtm':
                $files = GoogleDriveReader::getAllRtmFiles();
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
