<?php

namespace App\Models\BasicSettings;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageHeading extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'blog_page_title',
    'blog_details_page_title',
    'contact_page_title',
    'products_page_title',
    'product_details_page_title',
    'error_page_title',
    'faq_page_title',
    'forget_password_page_title',
    'login_page_title',
    'signup_page_title',
    'vendor_forget_password_page_title',
    'vendor_login_page_title',
    'vendor_signup_page_title',
    'cart_page_title',
    'checkout_page_title',
    'equipment_page_title',
    'equipment_details_page_title',
    'vendor_page_title',
  ];

  public function headingLang()
  {
    return $this->belongsTo(Language::class);
  }
}
