<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HomePage\Prominence\Feature;
use App\Models\HomePage\Prominence\FeatureSection;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FeatureController extends Controller
{
  public function sectionInfo(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->first();
    $information['language'] = $language;

    $information['data'] = $language->featureSection()->first();

    $information['features'] = $language->feature()->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    return view('backend.home-page.feature-section.index', $information);
  }

  public function updateSectionInfo(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->first();

    FeatureSection::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'subtitle' => $request->subtitle,
        'title' => $request->title,
        'text' => $request->text
      ]
    );

    Session::flash('success', 'Feature section updated successfully!');

    return redirect()->back();
  }


  public function storeFeature(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'icon' => 'required',
      'title' => 'required',
      'text' => 'required'
    ];

    $message = [
      'language_id.required' => 'The language field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    Feature::query()->create($request->except('language'));

    Session::flash('success', 'New feature added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateFeature(Request $request)
  {
    $rules = [
      'title' => 'required',
      'text' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $feature = Feature::query()->find($request->id);

    $feature->update($request->except('language'));

    Session::flash('success', 'Feature updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroyFeature($id)
  {
    $feature = Feature::query()->find($id);

    $feature->delete();

    return redirect()->back()->with('success', 'Feature deleted successfully!');
  }

  public function bulkDestroyFeature(Request $request)
  {
    $ids = $request['ids'];

    foreach ($ids as $id) {
      $feature = Feature::query()->find($id);

      $feature->delete();
    }

    Session::flash('success', 'Features deleted successfully!');

    return Response::json(['status' => 'success'], 200);
  }
}
