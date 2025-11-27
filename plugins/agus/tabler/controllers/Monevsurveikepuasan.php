<?php namespace Agus\Tabler\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class Monevsurveikepuasan extends Controller
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
        BackendMenu::setContext('Agus.Tabler', 'menu-implementasi-spmi', 'menu-struktur-monev-survei-kepuasan');
    }

      public function formExtendFields($form)
    {
        // Add custom CSS/JS assets
        $form->addJs('/plugins/agus/tabler/assets/js/mv-sur-kps.js');
    }

}
