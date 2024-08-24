<?php

use App\Models\Advertisement;
use App\Models\BasicSettings\Basic;
use App\Models\Transcation;
use App\Models\Instrument\EquipmentCategory;

use App\Models\Commission; // code by AG

if (!function_exists('createSlug')) {
  function createSlug($string)
  {
    $slug = preg_replace('/\s+/u', '-', trim($string));
    $slug = str_replace('/', '', $slug);
    $slug = str_replace('?', '', $slug);
    $slug = str_replace(',', '', $slug);

    return mb_strtolower($slug);
  }
}
if (!function_exists('make_input_name')) {
  function make_input_name($string)
  {
    return preg_replace('/\s+/u', '_', trim($string));
  }
}

if (!function_exists('replaceBaseUrl')) {
  function replaceBaseUrl($html, $type)
  {
    $startDelimiter = 'src=""';
    if ($type == 'summernote') {
      $endDelimiter = '/assets/img/summernote';
    } elseif ($type == 'pagebuilder') {
      $endDelimiter = '/assets/img';
    }

    $startDelimiterLength = strlen($startDelimiter);
    $endDelimiterLength = strlen($endDelimiter);
    $startFrom = $contentStart = $contentEnd = 0;

    while (false !== ($contentStart = strpos($html, $startDelimiter, $startFrom))) {
      $contentStart += $startDelimiterLength;
      $contentEnd = strpos($html, $endDelimiter, $contentStart);

      if (false === $contentEnd) {
        break;
      }

      $html = substr_replace($html, url('/'), $contentStart, $contentEnd - $contentStart);
      $startFrom = $contentEnd + $endDelimiterLength;
    }

    return $html;
  }
}

if (!function_exists('setEnvironmentValue')) {
  function setEnvironmentValue(array $values)
  {
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    if (count($values) > 0) {
      foreach ($values as $envKey => $envValue) {
        $str .= "\n"; // In case the searched variable is in the last line without \n
        $keyPosition = strpos($str, "{$envKey}=");
        $endOfLinePosition = strpos($str, "\n", $keyPosition);
        $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

        // If key does not exist, add it
        if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
          $str .= "{$envKey}={$envValue}\n";
        } else {
          $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        }
      }
    }

    $str = substr($str, 0, -1);

    if (!file_put_contents($envFile, $str)) return false;

    return true;
  }
}

if (!function_exists('showAd')) {
  function showAd($resolutionType)
  {
    $ad = Advertisement::where('resolution_type', $resolutionType)->inRandomOrder()->first();
    $adsenseInfo = Basic::query()->select('google_adsense_publisher_id')->first();

    if (!is_null($ad)) {
      if ($resolutionType == 1) {
        $maxWidth = '300px';
        $maxHeight = '250px';
      } else if ($resolutionType == 2) {
        $maxWidth = '300px';
        $maxHeight = '600px';
      } else {
        $maxWidth = '728px';
        $maxHeight = '90px';
      }

      if ($ad->ad_type == 'banner') {
        $markUp = '<a href="' . url($ad->url) . '" target="_blank" onclick="adView(' . $ad->id . ')" class="ad-banner">
          <img data-src="' . asset('assets/img/advertisements/' . $ad->image) . '" alt="advertisement" style="width: ' . $maxWidth . '; height: ' . $maxHeight . ';" class="lazy">
        </a>';

        return $markUp;
      } else {
        $markUp = '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . $adsenseInfo->google_adsense_publisher_id . '" crossorigin="anonymous"></script>
        <ins class="adsbygoogle" style="display: block;" data-ad-client="' . $adsenseInfo->google_adsense_publisher_id . '" data-ad-slot="' . $ad->slot . '" data-ad-format="auto" data-full-width-responsive="true"></ins>
        <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
        </script>';

        return $markUp;
      }
    } else {
      return;
    }
  }
}

if (!function_exists('get_href')) {
  function get_href($data)
  {
    $link_href = '';

    if ($data->type == 'home') {
      $link_href = route('index');
    } else if ($data->type == 'equipment') {
      $link_href = route('all_equipment');
    } else if ($data->type == 'vendor') {
      $link_href = route('frontend.vendors');
    } else if ($data->type == 'products') {
      $link_href = route('shop.products');
    } else if ($data->type == 'cart') {
      $link_href = route('shop.cart');
    } else if ($data->type == 'faq') {
      $link_href = route('faq');
    } else if ($data->type == 'contact') {
      $link_href = route('contact');
    } else if ($data->type == 'custom') {
      /**
       * this menu has created using menu-builder from the admin panel.
       * this menu will be used as drop-down or to link any outside url to this system.
       */
      if ($data->href == '') {
        $link_href = '#';
      } else {
        $link_href = $data->href;
      }
    } else {
      // this menu is for the custom page which has been created from the admin panel.
      $link_href = url('');
    }

    return $link_href;
  }
}

if (!function_exists('storeTranscation')) {
  function storeTranscation($data)
  {
    Transcation::create($data);
  }
}

// code by AG start
if (!function_exists('is_equipment_request_for_price')) {
  function is_equipment_request_for_price($equipment_category_id)
  {
    $category__ = EquipmentCategory::query()->where('id', $equipment_category_id)->first();
    return $category__->request_for_price;
  }
}

if (!function_exists('is_equipment_multiple_charges')) {
  function is_equipment_multiple_charges($equipment_category_id)
  {
    $category__ = EquipmentCategory::query()->where('id', $equipment_category_id)->first();
    return $category__->multiple_charges;
  }
}

if (!function_exists('is_equipment_temporary_toilet_type')) {
  function is_equipment_temporary_toilet_type($equipment_category_id)
  {
    if (env('TEMPORARY_TOILET_CATID') == $equipment_category_id) {
      return true;
    } else {
      return false;
    }
  }
}

if (!function_exists('is_equipment_storage_container_type')) {
  function is_equipment_storage_container_type($equipment_category_id)
  {
    if (env('STORAGE_CONTAINER_CATID') == $equipment_category_id) {
      return true;
    } else {
      return false;
    }
  }
}

if (!function_exists('get_category_placeholder_image')) {
  function get_category_placeholder_image($equipment_category_id)
  {
    $category__ = EquipmentCategory::query()->where('id', $equipment_category_id)->first();
    $img_url = '';
    if (isset($category__->placeholder_img) && $category__->placeholder_img != '') {
      $img_url = asset('assets/admin/img/category-images/' . $category__->placeholder_img);
    }

    return $img_url;
  }
}


if (!function_exists('amount_with_commission')) {
  function amount_with_commission($amount)
  {
    //calculate commission
    $percent = Commission::select('equipment_commission')->first();
    $commission = ($amount * $percent->equipment_commission) / 100;
    $amount = round(($amount + $commission), 2);
    return $amount;
  }
}

if (!function_exists('ag_calculate_commission')) {
  function ag_calculate_commission($amount)
  {
    //calculate commission
    $vendor_price = 0;
    $percent = Commission::select('equipment_commission')->first();

    $vendor_price = round((($amount * 100) / (100 + $percent->equipment_commission)), 2);

    $commission = round(($amount - $vendor_price), 2);
    return $commission;
  }
}
// code by AG end
