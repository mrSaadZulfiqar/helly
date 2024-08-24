<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\Section;
use App\Models\Instrument\Equipment;
use App\Models\Journal\Blog;
use App\Models\HomePage\Partner;
use App\Models\PlanFeature;
use App\Models\Shop\Product;

use App\Models\EquipmentFieldsValue; // code by AG

class HomeController extends Controller
{
  public function invoice()
  {
    return view('frontend.equipment.invoice');
  }
  public function index()
  {
  
    
    $themeVersion = Basic::query()->pluck('theme_version')->first();

    $secInfo = Section::query()->first();

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_home', 'meta_description_home')->first();

    if ($themeVersion == 1) {
      $queryResult['sliderInfos'] = $language->sliderInfo()->orderByDesc('id')->get();
    } else {
      $queryResult['heroSectionImage'] = Basic::query()->pluck('hero_section_image')->first();

      $queryResult['staticInfo'] = $language->staticSecInfo()->first();
    }

    $queryResult['equipCategories'] = $language->equipmentCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();

    if ($secInfo->about_section_status == 1) {
      $queryResult['aboutSectionImage'] = Basic::query()->pluck('about_section_image')->first();
      $queryResult['aboutSecInfo'] = $language->aboutSection()->first();
    }

    if ($secInfo->work_process_section_status == 1) {
      $queryResult['workProcessSecInfo'] = $language->workProcessSection()->first();
      $queryResult['processes'] = $language->workProcess()->orderBy('serial_number', 'asc')->get();
    }

    if ($secInfo->feature_section_status == 1) {
      $queryResult['featureSecInfo'] = $language->featureSection()->first();
      $queryResult['features'] = $language->feature()->orderByDesc('id')->get();
    }

    if ($secInfo->counter_section_status == 1) {
      $queryResult['counterSectionImage'] = Basic::query()->pluck('counter_section_image')->first();
      $queryResult['counters'] = $language->counterInfo()->orderByDesc('id')->get();
    }

    if ($secInfo->equipment_section_status == 1) {
      $queryResult['equipmentSecInfo'] = $language->equipmentSection()->first();

      $allEquipment = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
        ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment_contents.equipment_category_id')
        ->where('equipments.is_featured', '=', 'yes')
        ->where('equipment_contents.language_id', '=', $language->id)
        ->select('equipments.id', 'equipments.thumbnail_image', 'equipments.vendor_id', 'equipments.lowest_price', 'equipment_contents.title', 'equipment_contents.slug', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipment_categories.name as categoryName', 'equipment_categories.slug as categorySlug', 'equipment_contents.features', 'equipment_contents.equipment_category_id')
        ->orderByDesc('equipments.id')
        ->get();

      $allEquipment->map(function ($equipment) {
        $avgRating = $equipment->review()->avg('rating');
        $ratingCount = $equipment->review()->count();

        $equipment['avgRating'] = floatval($avgRating);
        $equipment['ratingCount'] = $ratingCount;
        
        // code by AG start
        $equipment_fields = EquipmentFieldsValue::where('equipment_id', $equipment->id)->first();
    
        if($equipment_fields){
            $information['equipment_fields'] = json_decode($equipment_fields->fields_value, true);
            $information['multiple_charges_settings'] = json_decode($equipment_fields->multiple_charges_settings, true);
        }
        else{
            $information['equipment_fields'] = array();
            $information['multiple_charges_settings'] = array();
        }
        
        $equipment['multiple_charges_settings'] = $information['multiple_charges_settings'];
      });

      $queryResult['allEquipment'] = $allEquipment;
    }

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    if ($secInfo->testimonial_section_status == 1) {
      $queryResult['testimonialSecInfo'] = $language->testimonialSection()->first();
      $queryResult['testimonials'] = $language->testimonial()->orderByDesc('id')->get();
    }

    if ($themeVersion == 1 && $secInfo->call_to_action_section_status == 1) {
      $queryResult['callToActionSectionImage'] = Basic::query()->pluck('call_to_action_section_image')->first();
      $queryResult['callToActionSecInfo'] = $language->callToActionSection()->first();
    }

    if ($themeVersion == 2 && $secInfo->product_section_status == 1) {
      $queryResult['productSecInfo'] = $language->productSection()->first();

      $queryResult['products'] = Product::query()->join('product_contents', 'products.id', '=', 'product_contents.product_id')
        ->where('products.is_featured', '=', 'yes')
        ->where('product_contents.language_id', '=', $language->id)
        ->select('products.id', 'products.featured_image', 'product_contents.title', 'product_contents.slug', 'products.current_price', 'products.previous_price')
        ->orderByDesc('products.id')
        ->get();
    }

    if ($secInfo->blog_section_status == 1) {
      $queryResult['blogSecInfo'] = $language->blogSection()->first();

      $queryResult['blogs'] = Blog::query()->join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
        ->join('blog_categories', 'blog_categories.id', '=', 'blog_informations.blog_category_id')
        ->where('blog_informations.language_id', '=', $language->id)
        ->select('blogs.image', 'blog_categories.name AS categoryName', 'blog_categories.slug AS categorySlug', 'blog_informations.title', 'blog_informations.slug', 'blog_informations.author', 'blogs.created_at', 'blog_informations.content')
        ->orderBy('blogs.serial_number', 'asc')
        ->get();
    }

    if ($secInfo->partner_section_status == 1) {
      $queryResult['partners'] = Partner::query()->orderBy('serial_number', 'asc')->get();
    }

    $queryResult['secInfo'] = $secInfo;

    return view('frontend.home.index-v3', $queryResult);

    // if ($themeVersion == 1) {
    //   return view('frontend.home.index-v1', $queryResult);
    // } else {
    //   return view('frontend.home.index-v2', $queryResult);
    // }
  }

  //offline
  public function offline()
  {
    return view('frontend.offline');
  }
  public function calculator()
  {
    return view('frontend.calculator');
  }
}
