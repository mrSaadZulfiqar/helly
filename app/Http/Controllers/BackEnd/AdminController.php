<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\UploadFile;
use App\Models\Admin;
use App\Models\Earning;
use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Journal\Blog;
use App\Models\HomePage\Partner;
use App\Models\Shop\Product;
use App\Models\Shop\ProductOrder;
use App\Models\Subscriber;
use App\Models\Transcation;
use App\Models\User;
use App\Rules\ImageMimeTypeRule;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\VendorSetting; // code by AG

class AdminController extends Controller
{
  public function login()
  {
    return view('backend.login');
  }

  public function authentication(Request $request)
  {
    $rules = [
      'username' => 'required',
      'password' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    // get the username and password which has provided by the admin
    $credentials = $request->only('username', 'password');

    if (Auth::guard('admin')->attempt($credentials)) {
      $authAdmin = Auth::guard('admin')->user();

      // check whether the admin's account is active or not
      if ($authAdmin->status == 0) {
        Session::flash('alert', 'Sorry, your account has been deactivated!');

        // logout auth admin as condition not satisfied
        Auth::guard('admin')->logout();

        return redirect()->back();
      } else {
        return redirect()->route('admin.dashboard');
      }
    } else {
      return redirect()->back()->with('alert', 'Oops, username or password does not match!');
    }
  }

  public function forgetPassword()
  {
    return view('backend.forget-password');
  }

  public function forgetPasswordMail(Request $request)
  {
    // validation start
    $rules = [
      'email' => [
        'required',
        'email:rfc,dns',
        new MatchEmailRule('admin')
      ]
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }
    // validation end

    // create a new password and store it in db
    $newPassword = uniqid();

    $admin = Admin::query()->where('email', '=', $request->email)->first();

    $admin->update([
      'password' => Hash::make($newPassword)
    ]);

    // prepare a mail to send newly created password to admin
    $mailData['subject'] = 'Reset Password';

    $mailData['body'] = 'Hi ' . $admin->first_name . ',<br/><br/>Your password has been reset. Your new password is: ' . $newPassword . '<br/>Now, you can login with your new password. You can change your password later.<br/><br/>Thank you.';

    $mailData['recipient'] = $admin->email;

    $mailData['sessionMessage'] = 'A mail has been sent to your email address.';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }
  
  // code by AG start
    public function get_unread_notifications(Request $request){
        $unread_n = Auth::guard('admin')->user()->unreadNotifications;
        $count__ = count($unread_n);
        
        $previous_notifications_count = $request['prev_count'];
        $has_new = 0;
        
        if($count__ > $previous_notifications_count){
            $has_new = 1;
        }
        $list_ = '';
        if(!empty($unread_n)){
            foreach ($unread_n as $notification) {
                
                $list_ .= '<li><div class="w-100 d-flex">' . $notification->data['msg'] . '<a href="' . route('read.notification', ['id' => $notification->id]) . '">Mark Read</a></div></li>';
            }
        }
        else{
            $list_ = '<li>No Notification</li>';
        }
              
        return response()->json(['drop_notifications' => $list_, 'noti_count'=>$count__,'has_new' => $has_new]);
    
    }
    // code by AG end

  public function redirectToDashboard()
  {
    $information['authAdmin'] = Auth::guard('admin')->user();

    $information['totalEquipment'] = Equipment::query()->count();
    $information['totalBooking'] = EquipmentBooking::query()->count();
    $information['totalProduct'] = Product::query()->count();
    $information['totalOrder'] = ProductOrder::query()->count();
    $information['totalBlog'] = Blog::query()->count();
    $information['totalUser'] = User::query()->count();
    $information['totalSubscriber'] = Subscriber::query()->count();
    $information['totalPartner'] = Partner::query()->count();

    $information['earning'] = Earning::first();

    $information['transcation_count'] = Transcation::get()->count();

    $monthWiseTotalBookings = DB::table('equipment_bookings')
      ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total_booking'))
      ->where('payment_status', '=', 'completed')
      ->groupBy('month')
      ->whereYear('created_at', '=', date('Y'))
      ->get();

    $monthWiseTotalIncomes = DB::table('equipment_bookings')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where('payment_status', '=', 'completed')
      ->groupBy('month')
      ->whereYear('created_at', '=', date('Y'))
      ->get();

    $months = [];
    $bookings = [];
    $incomes = [];

    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('M');
      array_push($months, $monthName);

      // get all 12 months's equipment booking
      $bookingFound = false;

      foreach ($monthWiseTotalBookings as $bookingInfo) {
        if ($bookingInfo->month == $i) {
          $bookingFound = true;
          array_push($bookings, $bookingInfo->total_booking);
          break;
        }
      }

      if ($bookingFound == false) {
        array_push($bookings, 0);
      }

      // get all 12 months's income of equipment booking
      $incomeFound = false;

      foreach ($monthWiseTotalIncomes as $incomeInfo) {
        if ($incomeInfo->month == $i) {
          $incomeFound = true;
          array_push($incomes, $incomeInfo->total);
          break;
        }
      }

      if ($incomeFound == false) {
        array_push($incomes, 0);
      }
    }

    $information['months'] = $months;
    $information['bookings'] = $bookings;
    $information['incomes'] = $incomes;

    return view('backend.admin.dashboard', $information);
  }

  public function changeTheme(Request $request)
  {
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['admin_theme_version' => $request->admin_theme_version]
    );

    return redirect()->back();
  }

  public function editProfile()
  {
    $adminInfo = Auth::guard('admin')->user();

    return view('backend.admin.edit-profile', compact('adminInfo'));
  }

  public function updateProfile(Request $request)
  {
    $admin = Auth::guard('admin')->user();

    $rules = [];

    if (is_null($admin->image)) {
      $rules['image'] = 'required';
    }
    if ($request->hasFile('image')) {
      $rules['image'] = new ImageMimeTypeRule();
    }

    $rules['username'] = [
      'required',
      Rule::unique('admins')->ignore($admin->id)
    ];

    $rules['email'] = [
      'required',
      'email:rfc,dns',
      Rule::unique('admins')->ignore($admin->id)
    ];

    $rules['first_name'] = 'required';

    $rules['last_name'] = 'required';

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('image')) {
      $newImg = $request->file('image');
      $oldImg = $admin->image;
      $imageName = UploadFile::update(public_path('assets/img/admins/'), $newImg, $oldImg);
    }

    $admin->update([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'image' => $request->hasFile('image') ? $imageName : $admin->image,
      'username' => $request->username,
      'email' => $request->email
    ]);

    Session::flash('success', 'Profile updated successfully!');

    return redirect()->back();
  }

  public function changePassword()
  {
    return view('backend.admin.change-password');
  }

  public function updatePassword(Request $request)
  {
    $rules = [
      'current_password' => [
        'required',
        new MatchOldPasswordRule('admin')
      ],
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];

    $messages = [
      'new_password.confirmed' => 'Password confirmation does not match.',
      'new_password_confirmation.required' => 'The confirm new password field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $admin = Auth::guard('admin')->user();

    $admin->update([
      'password' => Hash::make($request->new_password)
    ]);

    Session::flash('success', 'Password updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function logout(Request $request)
  {
    Auth::guard('admin')->logout();

    // invalidate the admin's session
    $request->session()->invalidate();

    return redirect()->route('admin.login');
  }

  //transcation 
  public function transcation(Request $request)
  {
    $transcation_id = null;
    if ($request->filled('transcation_id')) {
      $transcation_id = $request->transcation_id;
    }

    $transcations = Transcation::when($transcation_id, function ($query) use ($transcation_id) {
      return $query->where('transcation_id', 'like', '%' . $transcation_id . '%');
    })->orderBy('id', 'desc')->paginate(10);
    return view('backend.admin.transcation', compact('transcations'));
  }
  //destroy
  public function destroy(Request $request)
  {
    $transcation = Transcation::findOrFail($request->id);
    $transcation->delete();
    Session::flash('success', 'Transcation Deleted successfully!');

    return back();
  }

  //destroy
  public function bulk_destroy(Request $request)
  {
    $ids = $request->ids;
    foreach ($ids as $id) {
      $transcation = Transcation::findOrFail($id);
      $transcation->delete();
    }
    Session::flash('success', 'Transcation Deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }


  //monthly  earning
  public function monthly_earning(Request $request)
  {
    if ($request->filled('year')) {
      $date = $request->input('year');
    } else {
      $date = date('Y');
    }
    $monthWiseTotalIncomes = DB::table('transcations')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where('payment_status', 1)
      ->where(function ($query) {
        return $query->whereNotIn('transcation_type', [2, 3, 4]);
      })
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    $monthWiseTotalCommissions = DB::table('transcations')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(commission) as total'))
      ->where([['payment_status', 1], ['vendor_id', '!=', null]])
      ->where(function ($query) {
        return $query->whereNotIn('transcation_type', [2, 3, 4, 5]);
      })
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    $monthWiseTotaltaxs = DB::table('transcations')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(tax) as total'))
      ->where('payment_status', 1)
      ->where(function ($query) {
        return $query->whereNotIn('transcation_type', [2, 3, 4, 5]);
      })
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    $monthWiseTotalSDeposits = DB::table('transcations')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(security_deposit) as total'))
      ->where('payment_status', 1)
      ->where(function ($query) {
        return $query->whereIn('transcation_type', [1]);
      })
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();


    $months = [];
    $incomes = [];
    $commissions = [];
    $taxs = [];
    $sDeposits = [];
    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('F');
      array_push($months, $monthName);

      // get all 12 months's income of equipment booking
      $incomeFound = false;
      foreach ($monthWiseTotalIncomes as $incomeInfo) {
        if ($incomeInfo->month == $i) {
          $incomeFound = true;
          array_push($incomes, $incomeInfo->total);
          break;
        }
      }
      if ($incomeFound == false) {
        array_push($incomes, 0);
      }
      // get all 12 months's income of equipment booking
      $commissionFound = false;
      foreach ($monthWiseTotalCommissions as $commissionInfo) {
        if ($commissionInfo->month == $i) {
          $commissionFound = true;
          array_push($commissions, $commissionInfo->total);
          break;
        }
      }
      if ($commissionFound == false) {
        array_push($commissions, 0);
      }

      // get all 12 months's income of equipment booking
      $taxFound = false;
      foreach ($monthWiseTotaltaxs as $taxInfo) {
        if ($taxInfo->month == $i) {
          $taxFound = true;
          array_push($taxs, $taxInfo->total);
          break;
        }
      }
      if ($taxFound == false) {
        array_push($taxs, 0);
      }

      // get all 12 months's income of equipment booking
      $sDFound = false;
      foreach ($monthWiseTotalSDeposits as $monthWiseTotalSDeposit) {
        if ($monthWiseTotalSDeposit->month == $i) {
          $sDFound = true;
          array_push($sDeposits, $monthWiseTotalSDeposit->total);
          break;
        }
      }
      if ($sDFound == false) {
        array_push($sDeposits, 0);
      }
    }


    $information['months'] = $months;
    $information['incomes'] = $incomes;
    $information['commissions'] = $commissions;
    $information['taxs'] = $taxs;
    $information['sDeposits'] = $sDeposits;

    return view('backend.admin.earning', $information);
  }

  //monthly  income
  public function monthly_profit(Request $request)
  {
    if ($request->filled('year')) {
      $date = $request->input('year');
    } else {
      $date = date('Y');
    }
    $monthWiseTotalIncomes = DB::table('transcations')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(commission) as total'))
      ->where('payment_status', 1)
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();
    $monthWiseTotalProfits = DB::table('transcations')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(tax) as total'))
      ->where('payment_status', 1)
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();


    $months = [];
    $incomes = [];
    $taxs = [];
    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('M');
      array_push($months, $monthName);

      // get all 12 months's income of equipment booking
      $incomeFound = false;
      foreach ($monthWiseTotalIncomes as $incomeInfo) {
        if ($incomeInfo->month == $i) {
          $incomeFound = true;
          array_push($incomes, $incomeInfo->total);
          break;
        }
      }
      if ($incomeFound == false) {
        array_push($incomes, 0);
      }
      // get all 12 months's tax's of equipment booking
      $taxFound = false;
      foreach ($monthWiseTotalProfits as $profitInfo) {
        if ($profitInfo->month == $i) {
          $taxFound = true;
          array_push($taxs, $profitInfo->total);
          break;
        }
      }
      if ($taxFound == false) {
        array_push($taxs, 0);
      }
    }
    $information['months'] = $months;
    $information['incomes'] = $incomes;
    $information['taxs'] = $taxs;

    return view('backend.admin.profit', $information);
  }

  // code by AG start
  public function advance_route(){
      
        $information = array();
        
        
        $information['vendor_location'] = '';
        $information['vendor_latitude'] = '';
        $information['vendor_longitude'] = '';
        
        $information['booking_location'] = '';
        $information['booking_latitude'] = '';
        $information['booking_longitude'] = '';
        
        if(isset($_GET['booking']) && $_GET['booking'] != ''){
            $booking_ = EquipmentBooking::find($_GET['booking']);
            
            
            if(!empty($booking_) ){
                
                $vendor_id = $booking_->vendor_id;
                 $vendor_settings_ = VendorSetting::where('vendor_id',$vendor_id)->first();
                 
                $information['vendor_location'] = $vendor_settings_->location??'';
                $information['vendor_latitude'] = $vendor_settings_->latitude??'';
                $information['vendor_longitude'] = $vendor_settings_->longitude??'';
        
                $information['booking_location'] = $booking_->delivery_location;
                $information['booking_latitude'] = $booking_->lat;
                $information['booking_longitude'] = $booking_->lng;
            }
        }
    return view('backend.end-user.advance-route', $information);
  }
  // code by AG end
}
