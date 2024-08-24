<?php

namespace App\Models\Instrument;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityDepositRefund extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_id',
        'refund_type',
        'partial_amount',
        'status',
        'refund_status',
    ];

    public function booking()
    {
        return $this->belongsTo(EquipmentBooking::class, 'booking_id', 'id');
    }
}
