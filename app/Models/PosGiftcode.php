<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * Class PosGiftcode
 */
class PosGiftcode extends BaseModel
{
    protected $table = 'pos_giftcode';

    public $timestamps = true;

    public static function boot()
    {
        parent::boot();
    }

    protected $fillable = [
        'giftcode_id',
        'giftcode_place_id',
        'giftcode_code',
        'giftcode_price',
        'giftcode_surplus',
        'giftcode_sale_date',
        'giftcode_linkimage',
        'giftcode_image_front',
        'giftcode_sub_id',
        'giftcode_customer_id',
        'giftcode_type',
        'giftcode_status',
        'giftcode_redemption',
        'giftcode_balance',
        'giftcode_bonus_point'
    ];

    protected $guarded = [];

        
}