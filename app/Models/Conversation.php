<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
  use HasFactory;
  protected $fillable = [
    'vendor_id',
    'support_ticket_id',
    'reply',
    'file',
  ];
}
