<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorSetting extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vendor_settings';
    protected $fillable = [
        'vendor_id',
        'location',
        'latitude',
        'longitude',
        'provide_service',
        'unit',
        'signup_equipments',
        'weekends_delivery'
    ];
}
