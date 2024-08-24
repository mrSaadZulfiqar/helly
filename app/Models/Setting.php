<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Setting extends Model 
{
    use HasFactory;

    public $table = 'settings';

    public $fillable = [
        'key',
        'value',
        'tenant_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'key' => 'string',
        'value' => 'string',
    ];

    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }
}
