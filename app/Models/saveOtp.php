<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class saveOtp extends Model
{
    use HasFactory;
    public $table='save_otps';

    protected $fillable=[
        'email',
        'otp'
    ];
}
