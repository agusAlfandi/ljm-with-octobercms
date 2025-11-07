<?php namespace Agus\Tabler\Models;

use Model;

/**
 * Model
 */
class Informasi_publik extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_informasi_publik';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

       /**
     * @var array Attachment relations
     */
    public $attachOne = [
        'file' => 'System\\Models\\File',
    ];

}
