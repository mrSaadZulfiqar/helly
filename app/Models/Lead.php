<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'photo',
        'email',
        'phone',
        'username',
        'password',
        'status',
        'amount',
        'avg_rating',
        'self_pickup_status',
        'two_way_delivery_status',
        'email_verified_at',
        'show_email_addresss',
        'show_phone_number',
        'show_contact_form',
		'name',
        'shop_name',
        'country',
        'city',
        'state',
        'zip_code',
        'address',
        'details',
        'additional_contact',
        'converted_to_vendor'
    ];
}
