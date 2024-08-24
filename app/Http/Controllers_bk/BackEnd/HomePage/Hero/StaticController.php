<?php

namespace App\Http\Controllers\BackEnd\HomePage\Hero;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\HomePage\Hero\StaticSection;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class StaticController extends Controller
{
  public function index(Request $request)
  {
    $information['info'] = DB::table('basic_settings')->select('hero_section_image')->first();

    $language = Language::query()->where('code', '=', $request->language)->first();
    $information['language'] = $language;

    $information['data'] = $language->staticSecInfo()->first();

    $information['langs'] = Language::all();

    return view('backend.home-page.hero-section.static-version', $information);
  }

  public function updateImage(Request $request)
  {
    $data = DB::table('basic_settings')->select('hero_section_image')->first();

    $rules = [];

    if (empty($data->hero_section_image)) {
      $rules['hero_section_image'] = 'required';
    }
    if ($request->hasFile('hero_section_image')) {
      $rules['hero_section_image'] = new ImageMimeTypeRule();
    }

    $message = [
      'hero_section_image.required' => 'The image field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('hero_section_image')) {
      $newImage = $request->file('hero_section_image');
      $oldImage = $data->hero_section_image;

      $imgName = UploadFile::update(public_path('assets/img/hero/'), $newImage, $oldImage);

      // finally, store the image into db
      DB::table('basic_settings')->updateOrInsert(
        ['uniqid' => 12345],
        ['hero_section_image' => $imgName]
      );

      Session::flash('success', 'Image updated successfully!');
    }

    return redirect()->back();
  }


  public function updateInformation(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->first();

    StaticSection::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'title' => $request->title,
        'text' => $request->text,
        'button_name' => $request->button_name,
        'button_url' => $request->button_url
      ]
    );

    Session::flash('success', 'Hero section updated successfully!');

    return redirect()->back();
  }
}
