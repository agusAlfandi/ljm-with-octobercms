<?php namespace Agus\Tabler\Models;

use Model;

/**
 * Model
 */
class Page_header extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_page_header';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    /**
     * @var array Attachment relations
     */
    public $attachOne = [
        'image_1' => 'System\Models\File',
        'image_2' => 'System\Models\File',
        'image_3' => 'System\Models\File',
    ];

    /**
     * @var array fillable attributes for mass assignment.
     */
    protected $fillable = [
        // Hapus image_1, image_2, image_3 dari fillable 
        // karena attachment tidak perlu di fillable
    ];
}
