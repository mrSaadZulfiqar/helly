<?php

namespace App\Models;

use App\Models\RolePermission;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PlanFeature extends Model 
{
  use HasFactory;
    public function membership_plans()
    {
        return $this->belongsToMany(MembershipPlan::class, 'feature_plan', 'feature_id', 'plan_id');
    }
}