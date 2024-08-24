<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\HomePage\AboutSection;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class AboutController extends Controller
{
  public function index(Request $request)
  {
    $information['info'] = DB::table('basic_settings')->select('about_section_image')->first();

    $language = Language::query()->where('code', '=', $request->language)->first();
    $information['language'] = $language;

    $information['data'] = $language->aboutSection()->first();

    $information['langs'] = Language::all();

    return view('backend.home-page.about-section', $information);
  }

  public function updateImage(Request $request)
  {
    $data = DB::table('basic_settings')->select('about_section_image')->first();

    $rules = [];

    if (empty($data->about_section_image)) {
      $rules['about_section_image'] = 'required';
    }
    if ($request->hasFile('about_section_image')) {
      $rules['about_section_image'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('about_section_image')) {
      $newImage = $request->file('about_section_image');
      $oldImage = $data->about_section_image;

      $imgName = UploadFile::update(public_path('assets/img/'), $newImage, $oldImage);

      // finally, store the image into db
      DB::table('basic_settings')->updateOrInsert(
        ['uniqid' => 12345],
        ['about_section_image' => $imgName]
      );

      Session::flash('success', 'Image updated successfully!');
    }

    return redirect()->back();
  }


  public function update(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->first();

    AboutSection::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'subtitle' => $request->subtitle,
        'title' => $request->title,
        'text' => Purifier::clean($request->text),
        'button_name' => $request->button_name,
        'button_url' => $request->button_url
      ]
    );

    Session::flash('success', 'About section updated successfully!');

    return redirect()->back();
  }
}
