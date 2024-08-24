<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentQuote extends Model
{
    use HasFactory;
    protected $table = 'equipment_quotes';
    protected $fillable = [
        'equipment_id',
		'vendor_id',
		'customer_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'company_name',
        'project_country',
        'project_city',
        'project_state',
        'project_zipcode',
        'project_startdate',
        'worker_count',
        'details',
        'equipment_needed'
    ];
    
}
