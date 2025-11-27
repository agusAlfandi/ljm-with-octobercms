<?php namespace Agus\Tabler\Models;

use Model;

/**
 * Model
 */
class Benchmarking extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_benchmarking';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
