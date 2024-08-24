<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
  use HasFactory;
  protected $fillable = [
    'vendor_id',
    'email',
    'ticket_number',
    'subject',
    'description',
    'attachment',
    'status',
    'last_message',
  ];
  public function vendor()
  {
    return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
  }
  public function messages()
  {
    return $this->hasMany(Conversation::class);
  }
}
