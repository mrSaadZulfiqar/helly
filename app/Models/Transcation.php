<?php

namespace App\Models;

use App\Models\Instrument\EquipmentBooking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcation extends Model
{
    use HasFactory;
    protected $fillable = [
        'transcation_id',
        'booking_id',
        'transcation_type',
        'user_id',
        'vendor_id',
        'payment_status',
        'payment_method',
        'shipping_charge',
        'commission',
        'tax',
        'grand_total',
        'gateway_type',
        'currency_symbol',
        'currency_symbol_position',
        'pre_balance',
        'after_balance',
        'security_deposit'
    ];

    public function method()
    {
        return $this->belongsTo(WithdrawPaymentMethod::class, 'payment_method', 'id');
    }

    //room_booking 
    public function booking()
    {
        return $this->belongsTo(EquipmentBooking::class, 'booking_id', 'id');
    }
    //vendor_id
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
}
