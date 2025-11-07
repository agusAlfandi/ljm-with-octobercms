<?php namespace Agus\Tabler\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class Profile extends Controller
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
        BackendMenu::setContext('Agus.Tabler', 'main-menu-item', 'side-menu-profile');
    }

    public function index()
    {
        // Check if data exists
        $data = \Agus\Tabler\Models\Profile::first();
        
        // If no data, create empty record first
        if (!$data) {
            $data = \Agus\Tabler\Models\Profile::create([]);
        }
        
        // Always redirect to edit form
        return redirect('agus/tabler/profile/update/' . $data->id);
    }

    public function create()
    {
        // Check if data already exists
        $data = \Agus\Tabler\Models\Profile::first();
        if ($data) {
            \Flash::warning('Data profile sudah ada. Silakan edit data yang sudah ada.');
            return redirect('agus/tabler/profile/update/' . $data->id);
        }
        
        return $this->asExtension('FormController')->create();
    }

}
