<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminInfo extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_info';
    protected $fillable = [
        'user_id',
        'phone',
        'message_bird_phone',
        'voximplant_phone'
    ];
}
