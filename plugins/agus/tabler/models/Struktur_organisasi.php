<?php namespace Agus\Tabler\Models;

use Model;

/**
 * Model
 */
class Struktur_organisasi extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_struktur_organisasi';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    /**
     * @var array Attribute names to encode and decode using JSON.
     */
    public $jsonable = [];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $attachOne = [
        'file' => 'System\Models\File'
    ];

}
