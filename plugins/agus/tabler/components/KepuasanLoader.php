<?php

namespace Agus\Tabler\Components;

use Cms\Classes\ComponentBase;
use Agus\Tabler\Classes\GoogleDriveReader;

/**
 * Component that lazy-loads Laporan Survey Kepuasan data via AJAX.
 * Keeps PHP logic out of the page template.
 */
class KepuasanLoader extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Kepuasan Lazy Loader',
            'description' => 'Loads kepuasan Drive data on-demand via AJAX',
        ];
    }

    /**
     * AJAX handler – called from the frontend with POST { section: grf|svr|mnv|rtmk }
     */
    public function onGetKpsData()
    {
        $section = post('section');
        $files = [];

        switch ($section) {
            case 'grf':
                $files = GoogleDriveReader::getAllGrfKpsFiles();
                break;
            case 'svr':
                $files = GoogleDriveReader::getAllSvrKpsFiles();
                break;
            case 'mnv':
                $files = GoogleDriveReader::getAllMnvSvrKpsFiles();
                break;
            case 'rtmk':
                $files = GoogleDriveReader::getAllRtmKpsFiles();
                break;
        }

        $labels = [
            'grf'  => 'Grafik Kepuasan',
            'svr'  => 'Survei Kepuasan',
            'mnv'  => 'Monev Survei Kepuasan',
            'rtmk' => 'RTM Kepuasan',
        ];

        return [
            'section' => $section,
            'label'   => $labels[$section] ?? '',
            'data'    => $files,
        ];
    }
}
