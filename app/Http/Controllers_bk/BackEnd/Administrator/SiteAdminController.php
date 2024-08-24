<?php

namespace App\Http\Controllers\BackEnd\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Admin\StoreRequest;
use App\Http\Requests\Admin\UpdateRequest;
use App\Models\Admin;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use App\Models\AdminInfo;

class SiteAdminController extends Controller
{
  public function index()
  {
    $information['roles'] = RolePermission::all();

    $admins = Admin::query()->where('role_id', '!=', NULL)->get();

    $admins->map(function ($admin) {
      $role = $admin->role()->first();
      $admin['roleName'] = $role->name;
    });

    $information['admins'] = $admins;

    return view('backend.administrator.site-admin.index', $information);
  }

  public function store(StoreRequest $request)
  {
      $imageName = '';
      if($request->hasFile('image')){
          $imageName = UploadFile::store(public_path('assets/img/admins/'), $request->file('image'));
      }
      
    

    // code by AG start
    $in = $request->all();
    $admin_info_data = array();
    $admin_info_data['phone'] = $in['phone'];
    $admin_info_data['message_bird_phone'] = $in['message_bird_phone'];
    $admin_info_data['voximplant_phone'] = $in['voximplant_phone'];
    // code by AG end

    $admin = Admin::query()->create($request->except('image', 'password', 'phone', 'message_bird_phone', 'voximplant_phone') + [
      'image' => $imageName,
      'password' => Hash::make($request->password)
    ]);

    // code by AG start
    $admin_info_data['user_id'] = $admin->id;
    $admin_info = AdminInfo::create($admin_info_data);
    // code by AG end

    Session::flash('success', 'New admin added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function updateStatus(Request $request, $id)
  {
    $admin = Admin::query()->find($id);

    if ($request->status == 1) {
      $admin->update(['status' => 1]);
    } else {
      $admin->update(['status' => 0]);
    }

    Session::flash('success', 'Status updated successfully!');

    return redirect()->back();
  }

  public function update(UpdateRequest $request)
  {
    $admin = Admin::query()->find($request->id);

    if ($request->hasFile('image')) {
      $imageName = UploadFile::update(public_path('assets/img/admins/'), $request->file('image'), $admin->image);
    }

    // code by AG start
    $in = $request->all();
    $admin_info_data = array();
    $admin_info_data['phone'] = $in['phone'];
    $admin_info_data['message_bird_phone'] = $in['message_bird_phone'];
    $admin_info_data['voximplant_phone'] = $in['voximplant_phone'];


    // code by AG end
    
        $admin->update($request->except('image', 'phone','password', 'message_bird_phone', 'voximplant_phone') + [
          'image' => $request->hasFile('image') ? $imageName : $admin->image,
          'password' => $request->password ? Hash::make($request->password) : $admin->password
        ]);

    

    // code by AG start
    $admin_info = AdminInfo::where('user_id', $admin->id)->first();
    if( !empty( $admin_info )){
        $admin_info->update($admin_info_data);
    }else{
        $admin_info_data['user_id'] = $admin->id;
        $admin_info = AdminInfo::create($admin_info_data);
    }
    // code by AG end

    Session::flash('success', 'Admin updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $admin = Admin::query()->find($id);

    // delete admin profile picture
    @unlink(public_path('assets/img/admins/') . $admin->image);

    $admin->delete();

    return redirect()->back()->with('success', 'Admin deleted successfully!');
  }
}
