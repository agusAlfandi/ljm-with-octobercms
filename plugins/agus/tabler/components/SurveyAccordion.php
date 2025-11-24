<?php namespace Agus\Tabler\Components;

use Cms\Classes\ComponentBase;

class SurveyAccordion extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Survey Accordion',
            'description' => 'Displays files in accordion format for survey categories'
        ];
    }

    public function defineProperties()
    {
        return [
            'fileType' => [
                'title'       => 'File Type',
                'description' => 'Type of files to display (ami or rtm)',
                'default'     => 'ami',
                'type'        => 'dropdown',
                'options'     => ['ami' => 'AMI', 'rtm' => 'RTM']
            ],
            'categories' => [
                'title'       => 'Categories',
                'description' => 'Optional: Specific categories to show (comma separated). Leave empty to show all.',
                'default'     => '',
                'type'        => 'string',
                'placeholder' => 'e.g., Periode 1 ami, Periode 2 ami'
            ]
        ];
    }

    public function onRun()
    {
        $fileType = $this->property('fileType');

        // Load all files based on type
        if ($fileType == 'ami') {
            $this->page['amiFiles'] = \Agus\Tabler\Classes\GoogleDriveReader::getAllAmiFiles();
        } elseif ($fileType == 'rtm') {
            $this->page['rtmFiles'] = \Agus\Tabler\Classes\GoogleDriveReader::getAllRtmFiles();
        }

        // Parse categories if provided
        $categoriesString = $this->property('categories');
        if (!empty($categoriesString)) {
            $categories = array_map('trim', explode(',', $categoriesString));
            $this->page['categories'] = $categories;
        } else {
            $this->page['categories'] = [];
        }
    }
}
