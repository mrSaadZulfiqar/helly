<?php

namespace App\Models\Instrument;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
  use HasFactory;

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'vendor_id',
    'name',
    'charge',
    'serial_number',
    'additional_address',
    'radius',
    'zipcodes',
    'rate_type',
    'distance_rate',
    'equipment_category_id',
    'latitude',
    'longitude',
    'location_name'
  ];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }
  public function equipment() {
        return $this->hasOne(Equipment::class, 'location_id');
    }
}
