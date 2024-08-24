<?php

namespace App\Models\Instrument;

use App\Models\Instrument\EquipmentBooking;
use App\Models\Instrument\EquipmentContent;
use App\Models\Instrument\EquipmentReview;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
  use HasFactory;

  protected $table = 'equipments';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'vendor_id',
    'thumbnail_image',
    'slider_images',
    'quantity',
    'min_booking_days',
    'max_booking_days',
    'offer',
    'per_day_price',
    'per_week_price',
    'per_month_price',
    'lowest_price',
    'is_featured',
    'security_deposit_amount',
    'location_id',
    'base_price'
  ];

  public function content()
  {
    return $this->hasMany(EquipmentContent::class, 'equipment_id', 'id');
  }

  public function booking()
  {
    return $this->hasMany(EquipmentBooking::class, 'equipment_id', 'id');
  }

  public function review()
  {
    return $this->hasMany(EquipmentReview::class, 'equipment_id', 'id');
  }
  public function vendor()
  {
    return $this->belongsTo(Vendor::class);
  }
  
  
    public function location() {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
