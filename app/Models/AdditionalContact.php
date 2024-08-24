<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalContact extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'additional_contacts';
    protected $fillable = [
        'vendor_id',
        'email',
        'phone_full',
        'fax_no'
    ];
}
