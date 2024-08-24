<?php

namespace App\Models;

use App\Models\BasicSettings\CookieAlert;
use App\Models\BasicSettings\PageHeading;
use App\Models\BasicSettings\SEO;
use App\Models\CustomPage\PageContent;
use App\Models\FAQ;
use App\Models\Footer\FooterContent;
use App\Models\Footer\QuickLink;
use App\Models\HomePage\AboutSection;
use App\Models\HomePage\BlogSection;
use App\Models\HomePage\CallToActionSection;
use App\Models\HomePage\CounterInformation;
use App\Models\HomePage\EquipmentSection;
use App\Models\HomePage\Hero\Slider;
use App\Models\HomePage\Hero\StaticSection;
use App\Models\HomePage\Methodology\WorkProcess;
use App\Models\HomePage\Methodology\WorkProcessSection;
use App\Models\HomePage\ProductSection;
use App\Models\HomePage\Prominence\Feature;
use App\Models\HomePage\Prominence\FeatureSection;
use App\Models\HomePage\Testimony\Testimonial;
use App\Models\HomePage\Testimony\TestimonialSection;
use App\Models\Instrument\EquipmentCategory;
use App\Models\Instrument\EquipmentContent;
use App\Models\Instrument\Location;
use App\Models\Journal\BlogCategory;
use App\Models\Journal\BlogInformation;
use App\Models\MenuBuilder;
use App\Models\Popup;
use App\Models\Shop\ProductCategory;
use App\Models\Shop\ProductContent;
use App\Models\Shop\ShippingCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'code', 'direction', 'is_default'];

  public function pageName()
  {
    return $this->hasOne(PageHeading::class);
  }

  public function seoInfo()
  {
    return $this->hasOne(SEO::class);
  }

  public function cookieAlertInfo()
  {
    return $this->hasOne(CookieAlert::class);
  }

  public function faq()
  {
    return $this->hasMany(FAQ::class);
  }

  public function customPageInfo()
  {
    return $this->hasMany(PageContent::class);
  }

  public function footerContent()
  {
    return $this->hasOne(FooterContent::class);
  }

  public function footerQuickLink()
  {
    return $this->hasMany(QuickLink::class);
  }

  public function announcementPopup()
  {
    return $this->hasMany(Popup::class);
  }

  public function blogCategory()
  {
    return $this->hasMany(BlogCategory::class);
  }

  public function blogInformation()
  {
    return $this->hasMany(BlogInformation::class);
  }

  public function menuInfo()
  {
    return $this->hasOne(MenuBuilder::class, 'language_id', 'id');
  }

  public function sliderInfo()
  {
    return $this->hasMany(Slider::class, 'language_id', 'id');
  }

  public function aboutSection()
  {
    return $this->hasOne(AboutSection::class, 'language_id', 'id');
  }

  public function workProcessSection()
  {
    return $this->hasOne(WorkProcessSection::class, 'language_id', 'id');
  }

  public function workProcess()
  {
    return $this->hasMany(WorkProcess::class, 'language_id', 'id');
  }

  public function featureSection()
  {
    return $this->hasOne(FeatureSection::class, 'language_id', 'id');
  }

  public function feature()
  {
    return $this->hasMany(Feature::class, 'language_id', 'id');
  }

  public function counterInfo()
  {
    return $this->hasMany(CounterInformation::class, 'language_id', 'id');
  }

  public function equipmentSection()
  {
    return $this->hasOne(EquipmentSection::class, 'language_id', 'id');
  }

  public function testimonialSection()
  {
    return $this->hasOne(TestimonialSection::class, 'language_id', 'id');
  }

  public function testimonial()
  {
    return $this->hasMany(Testimonial::class, 'language_id', 'id');
  }

  public function callToActionSection()
  {
    return $this->hasOne(CallToActionSection::class, 'language_id', 'id');
  }

  public function blogSection()
  {
    return $this->hasOne(BlogSection::class, 'language_id', 'id');
  }

  public function shippingCharge()
  {
    return $this->hasMany(ShippingCharge::class);
  }

  public function productCategory()
  {
    return $this->hasMany(ProductCategory::class);
  }

  public function productContent()
  {
    return $this->hasMany(ProductContent::class);
  }

  public function equipmentCategory()
  {
    return $this->hasMany(EquipmentCategory::class);
  }

  public function location()
  {
    return $this->hasMany(Location::class, 'language_id', 'id');
  }

  public function equipmentContent()
  {
    return $this->hasMany(EquipmentContent::class, 'language_id', 'id');
  }

  public function staticSecInfo()
  {
    return $this->hasOne(StaticSection::class, 'language_id', 'id');
  }

  public function productSection()
  {
    return $this->hasOne(ProductSection::class, 'language_id', 'id');
  }
}
