<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosTemplateType extends Model
{
    protected $table = 'pos_template_type';

    public $timestamps = false;

    protected $fillable = [
        'template_type_id',
        'template_type_name',
        'template_type_status',
        'template_type_table_type',
    ];

    protected $guarded = [];

        
}