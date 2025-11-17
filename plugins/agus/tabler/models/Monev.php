<?php namespace Agus\Tabler\Models;

use Model;

/**
 * Model
 */
class Monev extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'agus_tabler_monev';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    /**
     * @var array Attributes that are mass assignable.
     */
    protected $fillable = [
        'file'
    ];

    /**
     * @var array Attributes to attach files.
     */
    public $attachOne = [
        'file' => 'System\Models\File'
    ];

    /**
     * Get the available category options.
     *
     * @return array
     */
    public static function getCategoryOptions()
    {
        return [
            1 => 'Beban Belajar Mahasiswa',
            2 => 'Monev Dosen',
            3 => 'Monev Kehadiran Mahasiswa',
            4 => 'Monev Materi dengan RPS',
            5 => 'Monev Nilai',
            6 => 'Monev UTS UAS -- RPS',
        ];
    }
}
