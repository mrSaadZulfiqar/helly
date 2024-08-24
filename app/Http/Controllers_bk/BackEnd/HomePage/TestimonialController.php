<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Testimonial\StoreRequest;
use App\Http\Requests\Testimonial\UpdateRequest;
use App\Models\HomePage\Testimony\Testimonial;
use App\Models\HomePage\Testimony\TestimonialSection;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TestimonialController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->first();
    $information['language'] = $language;

    $information['data'] = $language->testimonialSection()->first();

    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();

    $information['testimonials'] = $language->testimonial()->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    return view('backend.home-page.testimonial-section.index', $information);
  }

  public function updateSectionInfo(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->first();

    TestimonialSection::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'subtitle' => $request->subtitle,
        'title' => $request->title
      ]
    );

    Session::flash('success', 'Testimonial section updated successfully!');

    return redirect()->back();
  }


  public function storeTestimonial(StoreRequest $request)
  {
    $themeInfo = DB::table('basic_settings')->select('theme_version')->first();

    if ($themeInfo->theme_version == 2) {
      // store image in storage
      $imgName = UploadFile::store(public_path('assets/img/clients/'), $request->file('image'));
    }

    Testimonial::query()->create($request->except('language', 'image') + [
      'image' => $request->hasFile('image') ? $imgName : NULL
    ]);

    Session::flash('success', 'New testimonial added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function updateTestimonial(UpdateRequest $request)
  {
    $testimonial = Testimonial::query()->find($request->id);

    $themeInfo = DB::table('basic_settings')->select('theme_version')->first();

    if ($themeInfo->theme_version == 2 && $request->hasFile('image')) {
      $newImage = $request->file('image');
      $oldImage = $testimonial->image;
      $imgName = UploadFile::update(public_path('assets/img/clients/'), $newImage, $oldImage);
    }

    $testimonial->update($request->except('language', 'image') + [
      'image' => $request->hasFile('image') ? $imgName : $testimonial->image
    ]);

    Session::flash('success', 'Testimonial updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroyTestimonial($id)
  {
    $testimonial = Testimonial::query()->find($id);

    @unlink(public_path('assets/img/clients/') . $testimonial->image);

    $testimonial->delete();

    return redirect()->back()->with('success', 'Testimonial deleted successfully!');
  }

  public function bulkDestroyTestimonial(Request $request)
  {
    $ids = $request['ids'];

    foreach ($ids as $id) {
      $testimonial = Testimonial::query()->find($id);

      @unlink(public_path('assets/img/clients/') . $testimonial->image);

      $testimonial->delete();
    }

    Session::flash('success', 'Testimonials deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }
}
