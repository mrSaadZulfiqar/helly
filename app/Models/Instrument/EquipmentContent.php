<?php

namespace App\Models\Instrument;

use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentCategory;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentContent extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'equipment_category_id',
    'equipment_id',
    'title',
    'slug',
    'features',
    'description',
    'meta_keywords',
    'meta_description'
  ];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }

  public function category()
  {
    return $this->belongsTo(EquipmentCategory::class, 'equipment_category_id', 'id');
  }

  public function equipment()
  {
    return $this->belongsTo(Equipment::class, 'equipment_id', 'id');
  }
}
