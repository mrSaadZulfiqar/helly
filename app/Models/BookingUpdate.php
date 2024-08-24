<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingUpdate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'booking_id',
        'status',
        'status_type',
        'update_by_user_id',
        'user_type',
        'update_details'
    ];
    
    public function update_details(){
        $update_details = array();
        if($this->update_details != ''){
            $update_details = json_decode($this->update_details, true);
        }
        
        return $update_details;
    }
}
