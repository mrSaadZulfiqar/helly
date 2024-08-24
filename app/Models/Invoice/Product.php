<?php

namespace App\Models\Invoice;

use App\Traits\Multitenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'invoice_products';

    protected $fillable = [
        'name',
        'code',
        'category_id',
        'image',
        'unit_price',
        'description',
        'vendor_id'
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
