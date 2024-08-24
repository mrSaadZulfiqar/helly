<?php

namespace App\Models;

use App\Models\RolePermission;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class MembershipPlan extends Model 
{
    use HasFactory ;
    public function plan_features()
    {
        return $this->belongsToMany(PlanFeature::class, 'feature_plan', 'plan_id', 'feature_id');
    }


       
    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'plan_vendor', 'plan_id', 'vendor_id')->withPivot('created_at');
    }
}