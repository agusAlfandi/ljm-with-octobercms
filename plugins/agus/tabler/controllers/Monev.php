<?php namespace Agus\Tabler\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class Monev extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Agus.Tabler', 'menu-implementasi-spmi', 'menu-beban-belajar-mhs');
    }

    /**
     * formAfterSave - dipanggil setelah form disimpan
     * Trigger upload ke Google Drive di sini karena semua file attachment sudah committed
     */
    public function formAfterSave($model)
    {
        \Log::info('formAfterSave called', [
            'model_id' => $model->id,
            'title' => $model->title,
            'category' => $model->category,
            'has_file' => !is_null($model->file)
        ]);

        // Reload model untuk memastikan file relation sudah loaded
        $model = $model->fresh();

        if ($model->file && $model->category && $model->title) {
            \Log::info('Triggering Google Drive upload from controller', [
                'file_id' => $model->file->id,
                'file_path' => $model->file->getPath()
            ]);

            try {
                $filePath = $model->file->getLocalPath();
                $result = \Agus\Tabler\Classes\GoogleDriveUploader::uploadToCategory(
                    $filePath,
                    $model->category,
                    $model->title
                );

                if (isset($result['fileId'])) {
                    \Log::info('File uploaded to Google Drive from controller', [
                        'fileId' => $result['fileId'],
                        'fileName' => $result['fileName'] ?? $model->title,
                        'folderId' => $result['folderId'] ?? null,
                        'folderName' => $result['folderName'] ?? null
                    ]);

                    // Flash success message
                    \Flash::success(sprintf(
                        'File "%s" berhasil diupload ke Google Drive folder "%s"',
                        $result['fileName'] ?? $model->title,
                        $result['folderName'] ?? 'Google Drive'
                    ));
                }
            } catch (\Exception $e) {
                \Log::error('Google Drive upload failed in controller', [
                    'error' => $e->getMessage()
                ]);

                \Flash::warning('Data tersimpan, namun upload ke Google Drive gagal: ' . $e->getMessage());
            }
        }
    }

}
