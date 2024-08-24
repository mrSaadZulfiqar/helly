<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RecaptchaController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::query()->where('code', '=', $request->language)->first();
    $information['language'] = $language;

    // then, get the seo info of that language from db
    $information['data'] = Basic::first();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.basic-settings.recaptcha', $information);
  }

  public function update(Request $request)
  {
    $recaptcha = Basic::first();
    $recaptcha->google_recaptcha_site_key = $request->google_recaptcha_site_key;
    $recaptcha->google_recaptcha_secret_key = $request->google_recaptcha_secret_key;
    $recaptcha->google_recaptcha_status = $request->status;
    $recaptcha->update();
    return redirect()->back()->with('success','Recaptcha Informations updated successfully!');
  }
}