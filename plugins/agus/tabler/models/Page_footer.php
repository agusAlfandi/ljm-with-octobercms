<?php namespace Agus\Tabler\Models;

use Model;

/**
 * Model
 */
class Page_footer extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_page_footer';

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

}
