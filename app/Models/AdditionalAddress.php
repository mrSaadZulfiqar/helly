<?php

namespace App\Models;

use App\Models\Instrument\EquipmentBooking;
use App\Models\Instrument\EquipmentReview;
use App\Models\Shop\ProductOrder;
use App\Models\Shop\ProductReview;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class AdditionalAddress extends Model
{
  use HasFactory;
}