<?php

namespace App\Models\Instrument;

use App\Models\Instrument\Equipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentReview extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['user_id', 'vendor_id', 'equipment_id', 'comment', 'rating'];

  public function userInfo()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function equipmentInfo()
  {
    return $this->belongsTo(Equipment::class, 'equipment_id', 'id');
  }
}
