<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class SettingDataStore extends Model
{
    protected $table = 'tsd_rdw_settings';

    protected $fillable = [
        'name',
        'value'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
