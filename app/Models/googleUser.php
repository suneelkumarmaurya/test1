<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class googleUser extends Model
{
    use HasFactory;

    public $table='google_users';

    protected $fillable=[
        'name',
        'email',
        'password',
        'google_id'
    ];
}
