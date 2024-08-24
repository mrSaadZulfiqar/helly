<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_cards';
    
    protected $fillable = [
        'user_id',
        'card_number',
        'cvv',
        'exp_month',
        'exp_year',
        'first_name',
        'last_name',
        'address1',
        'address2',
        'city',
        'default',
        'location',
        'lat',
        'lng',
        'is_default',
        'branch_id',
        'stax_payment_method_id'
    ];
}
