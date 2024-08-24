<?php

namespace App\Models\Instrument;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
  use HasFactory;

  protected $table = 'equipment_coupons';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'code',
    'type',
    'value',
    'start_date',
    'end_date',
    'equipments'
  ];
}
