<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentFieldsValue extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'equipment_fields_value';
    protected $fillable = [
        'equipment_id',
        'fields_value',
        'multiple_charges_settings'
    ];
}
