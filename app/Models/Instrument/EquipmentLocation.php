<?php

namespace App\Models\Instrument;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'language_id',
        'equipment_id',
        'location_id',
    ];

    public function equiment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id', 'id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
