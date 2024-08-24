<?php

namespace App\Models;

use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentReview;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable, Notifiable;

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
    ];

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

    //review
    public function reviews()
    {
        return $this->hasMany(EquipmentReview::class, 'vendor_id', 'id');
    }

    //support ticket
    public function support_ticket()
    {
        return $this->hasMany(SupportTicket::class, 'vendor_id', 'id');
    }
    public function vendor_info()
    {
        return $this->hasOne(VendorInfo::class);
    }
     public function membership_plans()
    {
        return $this->belongsToMany(MembershipPlan::class, 'plan_vendor', 'vendor_id', 'plan_id')->withPivot('id','created_at','expiration_date','status','trial_days','is_trial_active','payment_status');
    }

    public function settings() {
       return $this->hasMany(Setting::class); 
    }
}
