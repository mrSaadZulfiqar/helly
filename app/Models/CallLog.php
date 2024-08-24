<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'call_logs';
    protected $fillable = [
        'call_id',
        'sourse',
        'destination',
        'status',
        'duration',
        'response',
        'team_id',
        'vendor_id',
        'service_provider'
    ];
}
