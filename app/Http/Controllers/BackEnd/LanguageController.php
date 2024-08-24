<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreRequest;
use App\Http\Requests\Language\UpdateRequest;
use App\Models\CustomPage\Page;
use App\Models\CustomPage\PageContent;
use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentContent;
use App\Models\Journal\Blog;
use App\Models\Journal\BlogInformation;
use App\Models\Language;
use App\Models\Shop\Product;
use App\Models\Shop\ProductContent;
use App\Models\Shop\ProductOrder;
use App\Models\Shop\ProductPurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $languages = Language::all();

    return view('backend.language.index', compact('languages'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreRequest $request)
  {
    // get all the keywords from the default file of language
    $data = file_get_contents(resource_path('lang/') . 'default.json');

    // make a new json file for the new language
    $fileName = strtolower($request->code) . '.json';

    // create the path where the new language json file will be stored
    $fileLocated = resource_path('lang/') . $fileName;

    // finally, put the keywords in the new json file and store the file in lang folder
    file_put_contents($fileLocated, $data);

    // then, store data in db
    Language::query()->create($request->all());

    Session::flash('success', 'Language added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Make a default language for this system.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function makeDefault($id)
  {
    // first, make other languages to non-default language
    $prevDefLang = Language::query()->where('is_default', '=', 1);

    $prevDefLang->update(['is_default' => 0]);

    // second, make the selected language to default language
    $language = Language::query()->find($id);

    $language->update(['is_default' => 1]);

    return back()->with('success', $language->name . ' ' . 'is set as default language.');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateRequest $request)
  {
    $language = Language::query()->find($request->id);

    if ($language->code !== $request->code) {
      /**
       * get all the keywords from the previous file,
       * which was named using previous language code
       */
      $data = file_get_contents(resource_path('lang/') . $language->code . '.json');

      // make a new json file for the new language (code)
      $fileName = strtolower($request->code) . '.json';

      // create the path where the new language (code) json file will be stored
      $fileLocated = resource_path('lang/') . $fileName;

      // then, put the keywords in the new json file and store the file in lang folder
      file_put_contents($fileLocated, $data);

      // now, delete the previous language code file
      @unlink(resource_path('lang/') . $language->code . '.json');

      // finally, update the info in db
      $language->update($request->all());
    } else {
      $language->update($request->all());
    }

    Session::flash('success', 'Language updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Display all the keywords of specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function editKeyword($id)
  {
    $language = Language::query()->find($id);

    // get all the keywords of the selected language
    $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');

    // convert json encoded string into a php associative array
    $keywords = json_decode($jsonData);

    return view('backend.language.edit-keyword', compact('language', 'keywords'));
  }

  public function addKeyword(Request $request)
  {
    $rules = [
      'keyword' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }
    $languages = Language::get();
    foreach ($languages as $language) {
      // get all the keywords of the selected language
      $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');

      // convert json encoded string into a php associative array
      $keywords = json_decode($jsonData, true);
      $datas = [];
      $datas[$request->keyword] = $request->keyword;

      foreach ($keywords as $key => $keyword) {
        $datas[$key] = $keyword;
      }
      //put data
      $jsonData = json_encode($datas);

      $fileLocated = resource_path('lang/') . $language->code . '.json';

      // put all the keywords in the selected language file
      file_put_contents($fileLocated, $jsonData);
    }

    //for default json
    // get all the keywords of the selected language
    $jsonData = file_get_contents(resource_path('lang/') . 'default.json');

    // convert json encoded string into a php associative array
    $keywords = json_decode($jsonData, true);
    $datas = [];
    $datas[$request->keyword] = $request->keyword;

    foreach ($keywords as $key => $keyword) {
      $datas[$key] = $keyword;
    }
    //put data
    $jsonData = json_encode($datas);

    $fileLocated = resource_path('lang/') . 'default.json';

    // put all the keywords in the selected language file
    file_put_contents($fileLocated, $jsonData);

    Session::flash('success', 'A new keyword add successfully');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Update the keywords of specified resource in respective json file.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateKeyword(Request $request, $id)
  {
    $arrData = $request['keyValues'];

    // first, check each key has value or not
    foreach ($arrData as $key => $value) {
      if ($value == null) {
        Session::flash('warning', 'Value is required for "' . $key . '" key.');

        return redirect()->back();
      }
    }

    $jsonData = json_encode($arrData);

    $language = Language::query()->find($id);

    $fileLocated = resource_path('lang/') . $language->code . '.json';

    // put all the keywords in the selected language file
    file_put_contents($fileLocated, $jsonData);

    Session::flash('success', $language->name . ' language\'s keywords updated successfully!');

    return redirect()->back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $language = Language::query()->find($id);

    if ($language->is_default == 1) {
      return redirect()->back()->with('warning', 'Default language cannot be delete.');
    } else {
      /**
       * delete about-section info
       */
      $aboutSecInfo = $language->aboutSection()->first();

      if (!empty($aboutSecInfo)) {
        $aboutSecInfo->delete();
      }

      /**
       * delete blog infos
       */
      $blogInfos = $language->blogInformation()->get();

      if (count($blogInfos) > 0) {
        foreach ($blogInfos as $blogData) {
          $blogInfo = $blogData;
          $blogData->delete();

          // delete the blog if, this blog does not contain any other blog-information in any other language
          $otherBlogInfos = BlogInformation::query()->where('language_id', '<>', $language->id)
            ->where('blog_id', '=', $blogInfo->blog_id)
            ->get();

          if (count($otherBlogInfos) == 0) {
            $blog = Blog::query()->find($blogInfo->blog_id);
            @unlink(public_path('assets/img/blogs/') . $blog->image);
            $blog->delete();
          }
        }
      }

      /**
       * delete blog-categories info
       */
      $blogCategories = $language->blogCategory()->get();

      if (count($blogCategories) > 0) {
        foreach ($blogCategories as $blogCategory) {
          $blogCategory->delete();
        }
      }

      /**
       * delete blog-section info
       */
      $blogSecInfo = $language->blogSection()->first();

      if (!empty($blogSecInfo)) {
        $blogSecInfo->delete();
      }

      /**
       * delete call-to-action-section info
       */
      $callToActionSecInfo = $language->callToActionSection()->first();

      if (!empty($callToActionSecInfo)) {
        $callToActionSecInfo->delete();
      }

      /**
       * delete cookie-alert info
       */
      $cookieAlertInfo = $language->cookieAlertInfo()->first();

      if (!empty($cookieAlertInfo)) {
        $cookieAlertInfo->delete();
      }

      /**
       * delete counter info
       */
      $counterInfos = $language->counterInfo()->get();

      if (count($counterInfos) > 0) {
        foreach ($counterInfos as $counterInfo) {
          $counterInfo->delete();
        }
      }

      /**
       * delete equipment infos
       */
      $equipmentInfos = $language->equipmentContent()->get();

      if (count($equipmentInfos) > 0) {
        foreach ($equipmentInfos as $equipmentData) {
          $equipmentInfo = $equipmentData;
          $equipmentData->delete();

          // delete the equipment if, this equipment does not contain any other equipment-contents in any other language
          $otherEquipmentInfos = EquipmentContent::query()->where('language_id', '<>', $language->id)
            ->where('equipment_id', '=', $equipmentInfo->equipment_id)
            ->get();

          if (count($otherEquipmentInfos) == 0) {
            $equipment = Equipment::query()->find($equipmentInfo->equipment_id);

            // delete all the bookings of this equipment
            $bookings = $equipment->booking()->get();

            if (count($bookings) > 0) {
              foreach ($bookings as $booking) {
                @unlink(public_path('assets/file/attachments/equipment/') . $booking->attachment);

                @unlink(public_path('assets/file/invoices/equipment/') . $booking->invoice);

                $booking->delete();
              }
            }

            // delete all the reviews of this equipment
            $equipmentReviews = $equipment->review()->get();

            if (count($equipmentReviews) > 0) {
              foreach ($equipmentReviews as $equipmentReview) {
                $equipmentReview->delete();
              }
            }

            // delete thumbnail image of this equipment
            $thumbImg = $equipment->thumbnail_image;
            @unlink(public_path('assets/img/equipments/thumbnail-images/') . $thumbImg);

            // delete slider images of this equipment
            $sldImgs = json_decode($equipment->slider_images);

            foreach ($sldImgs as $sldImg) {
              @unlink(public_path('assets/img/equipments/slider-images/') . $sldImg);
            }

            $equipment->delete();
          }
        }
      }

      /**
       * delete equipment-categories info
       */
      $equipmentCategories = $language->equipmentCategory()->get();

      if (count($equipmentCategories) > 0) {
        foreach ($equipmentCategories as $equipmentCategory) {
          $equipmentCategory->delete();
        }
      }

      /**
       * delete equipment-section info
       */
      $equipmentSecInfo = $language->equipmentSection()->first();

      if (!empty($equipmentSecInfo)) {
        $equipmentSecInfo->delete();
      }

      /**
       * delete faq infos
       */
      $faqs = $language->faq()->get();

      if (count($faqs) > 0) {
        foreach ($faqs as $faq) {
          $faq->delete();
        }
      }

      /**
       * delete feature infos
       */
      $features = $language->feature()->get();

      if (count($features) > 0) {
        foreach ($features as $feature) {
          $feature->delete();
        }
      }

      /**
       * delete feature-section info
       */
      $featureSecInfo = $language->featureSection()->first();

      if (!empty($featureSecInfo)) {
        $featureSecInfo->delete();
      }

      /**
       * delete footer-content info
       */
      $footerContentInfo = $language->footerContent()->first();

      if (!empty($footerContentInfo)) {
        $footerContentInfo->delete();
      }

      /**
       * delete shipping-location infos
       */
      $locations = $language->location()->get();

      if (count($locations) > 0) {
        foreach ($locations as $location) {
          $location->delete();
        }
      }

      /**
       * delete website-menu info
       */
      $websiteMenuInfo = $language->menuInfo()->first();

      if (!empty($websiteMenuInfo)) {
        $websiteMenuInfo->delete();
      }

      /**
       * delete custom-page infos
       */
      $customPageInfos = $language->customPageInfo()->get();

      if (count($customPageInfos) > 0) {
        foreach ($customPageInfos as $customPageData) {
          $customPageInfo = $customPageData;
          $customPageData->delete();

          // delete the custom-page if, this page does not contain any other page-content in any other language
          $otherPageContents = PageContent::query()->where('language_id', '<>', $language->id)
            ->where('page_id', '=', $customPageInfo->page_id)
            ->get();

          if (count($otherPageContents) == 0) {
            $page = Page::query()->find($customPageInfo->page_id);
            $page->delete();
          }
        }
      }

      /**
       * delete page-heading info
       */
      $pageHeadingInfo = $language->pageName()->first();

      if (!empty($pageHeadingInfo)) {
        $pageHeadingInfo->delete();
      }

      /**
       * delete popup infos
       */
      $popups = $language->announcementPopup()->get();

      if (count($popups) > 0) {
        foreach ($popups as $popup) {
          @unlink(public_path('assets/img/popups/') . $popup->image);
          $popup->delete();
        }
      }

      /**
       * delete product infos
       */
      $productInfos = $language->productContent()->get();

      if (count($productInfos) > 0) {
        foreach ($productInfos as $productData) {
          $productInfo = $productData;
          $productData->delete();

          // delete the product if, this product does not contain any other product-contents in any other language
          $otherProductInfos = ProductContent::query()->where('language_id', '<>', $language->id)
            ->where('product_id', '=', $productInfo->product_id)
            ->get();

          if (count($otherProductInfos) == 0) {
            $product = Product::query()->find($productInfo->product_id);

            // delete purchase item records of this product
            $purchaseInfos = $product->purchase()->get();

            if (count($purchaseInfos) > 0) {
              foreach ($purchaseInfos as $purchaseData) {
                $purchaseInfo = $purchaseData;
                $purchaseData->delete();

                // delete the order if, this order does not contain any other items
                $otherPurchaseItems = ProductPurchaseItem::query()->where('product_id', '<>', $product->id)
                  ->where('product_order_id', '=', $purchaseInfo->product_order_id)
                  ->get();

                if (count($otherPurchaseItems) == 0) {
                  $order = ProductOrder::query()->find($purchaseInfo->product_order_id);

                  // delete order receipt
                  @unlink(public_path('assets/file/attachments/product/') . $order->receipt);

                  // delete order invoice
                  @unlink(public_path('assets/file/invoices/product/') . $order->invoice);

                  $order->delete();
                }
              }
            }

            // delete all the reviews of this product
            $reviews = $product->review()->get();

            if (count($reviews) > 0) {
              foreach ($reviews as $review) {
                $review->delete();
              }
            }

            // delete product featured image
            $featImg = $product->featured_image;
            @unlink(public_path('assets/img/products/featured-images/') . $featImg);

            // delete product slider images
            $sldImgs = json_decode($product->slider_images);

            foreach ($sldImgs as $sldImg) {
              @unlink(public_path('assets/img/products/slider-images/') . $sldImg);
            }

            // delete product zip file
            $zipFile = $product->file;
            @unlink(public_path('assets/file/products/') . $zipFile);

            $product->delete();
          }
        }
      }

      /**
       * delete product-categories info
       */
      $productCategories = $language->productCategory()->get();

      if (count($productCategories) > 0) {
        foreach ($productCategories as $productCategory) {
          $productCategory->delete();
        }
      }

      /**
       * delete product-section info
       */
      $productSecInfo = $language->productSection()->first();

      if (!empty($productSecInfo)) {
        $productSecInfo->delete();
      }

      /**
       * delete product-shipping-charge infos
       */
      $charges = $language->shippingCharge()->get();

      if (count($charges) > 0) {
        foreach ($charges as $charge) {
          $charge->delete();
        }
      }

      /**
       * delete footer-quick-links
       */
      $quickLinks = $language->footerQuickLink()->get();

      if (count($quickLinks) > 0) {
        foreach ($quickLinks as $quickLink) {
          $quickLink->delete();
        }
      }

      /**
       * delete seo info
       */
      $seoInfo = $language->seoInfo()->first();

      if (!empty($seoInfo)) {
        $seoInfo->delete();
      }

      /**
       * delete hero-slider infos
       */
      $sliders = $language->sliderInfo()->get();

      if (count($sliders) > 0) {
        foreach ($sliders as $slider) {
          $bgImg = $slider->background_image;

          @unlink(public_path('assets/img/hero/sliders/') . $bgImg);
          $slider->delete();
        }
      }

      /**
       * delete hero-static info
       */
      $staticSecInfo = $language->staticSecInfo()->first();

      if (!empty($staticSecInfo)) {
        $staticSecInfo->delete();
      }

      /**
       * delete testimonial infos
       */
      $testimonials = $language->testimonial()->get();

      if (count($testimonials) > 0) {
        foreach ($testimonials as $testimonial) {
          $clientImg = $testimonial->image;

          @unlink(public_path('assets/img/clients/') . $clientImg);
          $testimonial->delete();
        }
      }

      /**
       * delete testimonial-section info
       */
      $testimonialSecInfo = $language->testimonialSection()->first();

      if (!empty($testimonialSecInfo)) {
        $testimonialSecInfo->delete();
      }

      /**
       * delete work-process infos
       */
      $processes = $language->workProcess()->get();

      if (count($processes) > 0) {
        foreach ($processes as $process) {
          $process->delete();
        }
      }

      /**
       * delete work-process-section info
       */
      $workProcessSecInfo = $language->workProcessSection()->first();

      if (!empty($workProcessSecInfo)) {
        $workProcessSecInfo->delete();
      }

      /**
       * delete the language json file
       */
      @unlink(resource_path('lang/') . $language->code . '.json');

      /**
       * finally, delete the language info from db
       */
      $language->delete();

      return redirect()->back()->with('success', 'Language deleted successfully!');
    }
  }

  /**
   * Check the specified language is RTL or not.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function checkRTL($id)
  {
    if (!is_null($id)) {
      $direction = Language::query()->where('id', '=', $id)
        ->pluck('direction')
        ->first();

      return response()->json(['successData' => $direction], 200);
    } else {
      return response()->json(['errorData' => 'Sorry, an error has occured!'], 400);
    }
  }
}
