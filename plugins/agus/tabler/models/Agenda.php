<?php namespace Agus\Tabler\Models;

use Model;

/**
 * Model
 */
class Agenda extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_agenda_ljm';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
