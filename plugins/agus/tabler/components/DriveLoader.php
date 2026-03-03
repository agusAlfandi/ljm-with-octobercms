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
        }

        $labels = [
            'ami' => 'Audit Mutu Internal',
            'rtm' => 'Rapat Tinjauan Manajemen',
        ];

        return [
            'type'  => $type,
            'label' => $labels[$type] ?? '',
            'data'  => $files,
        ];
    }
}
