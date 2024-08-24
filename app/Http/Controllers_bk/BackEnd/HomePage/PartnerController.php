<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\HomePage\Partner;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
  public function index()
  {
    $partners = Partner::orderByDesc('id')->get();

    return view('backend.home-page.partners.index', compact('partners'));
  }

  public function store(Request $request)
  {
    $rules = [
      'image' => [
        'required',
        $request->hasFile('image') ? new ImageMimeTypeRule() : ''
      ],
      'url' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $imageName = UploadFile::store(public_path('assets/img/partners/'), $request->file('image'));

    Partner::create($request->except('image') + [
      'image' => $imageName
    ]);

    Session::flash('success', 'New partner added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'image' => $request->hasFile('image') ? new ImageMimeTypeRule() : '',
      'url' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $partner = Partner::findOrFail($request->id);

    if ($request->hasFile('image')) {
      $newImage = $request->file('image');
      $oldImage = $partner->image;
      $imageName = UploadFile::update(public_path('assets/img/partners/'), $newImage, $oldImage);
    }

    $partner->update($request->except('image') + [
      'image' => $request->hasFile('image') ? $imageName : $partner->image
    ]);

    Session::flash('success', 'Partner updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy(Request $request, $id)
  {
    $partner = Partner::findOrFail($id);

    @unlink(public_path('assets/img/partners/') . $partner->image);

    $partner->delete();

    return redirect()->back()->with('success', 'Partner deleted successfully!');
  }
}
