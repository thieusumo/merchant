<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
	protected $table = 'pos_password_resets';
    protected $fillable = [
        'email', 'token'
    ];
}