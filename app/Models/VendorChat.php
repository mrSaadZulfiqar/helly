<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorChat extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vendorchat';
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'sender_number',
        'receiver_number',
        'msg',
        'service_provider'
    ];
}
