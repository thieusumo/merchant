<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PosWebsiteProperty extends Model
{
    protected $table = 'pos_website_properties';

	public $timestamps = true;

    // protected $primaryKey = "wp_variable";

    protected $fillable = [
        'wp_variable',
        'wp_place_id',
        'wp_name',
        'wp_value',
        'wp_type',
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];
        
}