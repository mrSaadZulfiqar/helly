<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;

class Driver extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable, Notifiable;
    
    protected $fillable = [
        'first_name',
        'last_name',
        'image',
        'email',
        'username',
        'password',
        'contact_number',
        'address',
        'city',
        'state',
        'country',
        'zipcode',
        'status',
        'vendor_id',
        'language_id'
    ];
}
