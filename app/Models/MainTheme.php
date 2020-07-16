<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MainTheme extends Model
{
    protected $table = 'main_theme';

    public $timestamps = true;

    protected $fillable = [
        'theme_id',
        'theme_name',
        'theme_image',
        'theme_url',
        'theme_price',
        'theme_descript',
        'theme_name_temp',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'theme_status',
        'theme_license',
    ];

    protected $guarded = [];

        
}