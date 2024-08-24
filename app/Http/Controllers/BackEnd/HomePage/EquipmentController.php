<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HomePage\EquipmentSection;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EquipmentController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->first();
    $information['language'] = $language;

    $information['data'] = $language->equipmentSection()->first();

    $information['langs'] = Language::all();

    return view('backend.home-page.equipment-section', $information);
  }

  public function update(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->first();

    EquipmentSection::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'subtitle' => $request->subtitle,
        'title' => $request->title,
        'text' => $request->text
      ]
    );

    Session::flash('success', 'Equipment section updated successfully!');

    return redirect()->back();
  }
}
