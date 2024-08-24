<?php

namespace App\Models\Instrument;

use App\Models\Instrument\EquipmentContent;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['language_id', 'name', 'slug', 'status', 'serial_number', 'request_for_price', 'placeholder_img', 'multiple_charges'];

  public function language()
  {
    return $this->belongsTo(Language::class);
  }

  public function equipmentContent()
  {
    return $this->hasMany(EquipmentContent::class, 'equipment_category_id', 'id');
  }
}
