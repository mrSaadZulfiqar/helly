<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteItemTax extends Model
{
    use HasFactory;

    public static $rules = [
        'quote_item_id' => 'required',
        'tax_id' => 'required',
        'tax' => 'nullable',
    ];

    protected $table = 'quote_item_taxes';

    public $fillable = [
        'quote_item_id',
        'tax_id',
        'tax',
    ];

    protected $casts = [
        'quote_item_id' => 'integer',
        'tax_id' => 'integer',
        'tax' => 'double',
    ];

    public function quoteItem(): BelongsTo
    {
        return $this->belongsTo(QuoteItem::class);
    }
}
