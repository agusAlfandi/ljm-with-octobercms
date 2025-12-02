<?php namespace Agus\Tabler\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class Akred_perguruan_tinggi extends Controller
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
        BackendMenu::setContext('Agus.Tabler', 'main-menu-akreditasi', 'menu-struktur-akred-perguruan-tinggi');
    }

}
