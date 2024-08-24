<?php

namespace App\Http\Controllers\BackEnd\User;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\BranchUser;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Http;
use Illuminate\Validation\Rule;
use App\Models\Admin;
use App\Http\Helpers\BasicMailer;
use App\Notifications\BasicNotify; // code by AG

class UserController extends Controller
{
  public function index(Request $request)
  {
    $searchKey = null;

    if ($request->filled('info')) {
      $searchKey = $request['info'];
    }

    $users = User::query()->when($searchKey, function ($query, $searchKey) {
      return $query->where('username', 'like', '%' . $searchKey . '%')
        ->orWhere('email', 'like', '%' . $searchKey . '%');
    })
      ->orderByDesc('id')
      ->paginate(10);

    return view('backend.end-user.user.index', compact('users'));
  }

  public function updateEmailStatus(Request $request, $id)
  {
    $user = User::query()->find($id);

    if ($request['email_status'] == 'verified') {
      $user->update([
        'email_verified_at' => date('Y-m-d H:i:s')
      ]);
    } else {
      $user->update([
        'email_verified_at' => NULL
      ]);
    }

    Session::flash('success', 'Email status updated successfully!');

    return redirect()->back();
  }

  public function updateAccountStatus(Request $request, $id)
  {
    $user = User::query()->find($id);

    if ($request['account_status'] == 1) {
      $user->update([
        'status' => 1
      ]);
    } else {
      $user->update([
        'status' => 0
      ]);
    }

    Session::flash('success', 'Account status updated successfully!');

    return redirect()->back();
  }

  public function show($id)
  {
    $user = User::query()->find($id);
    $information['userInfo'] = $user;

    $bookings = $user->equipmentBooking()->orderByDesc('id')->paginate(10);

    $language = Language::query()->where('is_default', '=', 1)->first();

    $bookings->map(function ($booking) use ($language) {
      $equipment = $booking->equipmentInfo()->first();
      $booking['equipmentTitle'] = $equipment->content()->where('language_id', $language->id)->pluck('title')->first();
    });

    $information['bookings'] = $bookings;

    $information['basicData'] = Basic::query()->select('self_pickup_status', 'two_way_delivery_status')->first();

    return view('backend.end-user.user.details', $information);
  }

  public function changePassword($id)
  {
    $userInfo = User::query()->find($id);

    return view('backend.end-user.user.change-password', compact('userInfo'));
  }

  public function updatePassword(Request $request, $id)
  {
    $rules = [
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

    $user = User::query()->find($id);

    $user->update([
      'password' => Hash::make($request->new_password)
    ]);

    Session::flash('success', 'Password updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $user = User::query()->find($id);

    // delete all the equipment bookings of this user
    $bookings = $user->equipmentBooking()->get();

    if (count($bookings) > 0) {
      foreach ($bookings as $booking) {
        @unlink(public_path('assets/file/attachments/equipment/') . $booking->attachment);
        @unlink(public_path('assets/file/invoices/equipment/') . $booking->invoice);

        $booking->delete();
      }
    }

    // delete all the equipment reviews of this user
    $equipmentReviews = $user->equipmentReview()->get();

    if (count($equipmentReviews) > 0) {
      foreach ($equipmentReviews as $review) {
        $review->delete();
      }
    }

    // delete all the product orders of this user
    $orders = $user->productOrder()->get();

    if (count($orders) > 0) {
      foreach ($orders as $order) {
        @unlink(public_path('assets/file/attachments/product/') . $order->attachment);
        @unlink(public_path('assets/file/invoices/product/') . $order->invoice);

        // delete all the purchased items of this order
        $items = $order->item()->get();

        foreach ($items as $item) {
          $item->delete();
        }

        $order->delete();
      }
    }

    // delete all the product reviews of this user
    $productReviews = $user->productReview()->get();

    if (count($productReviews) > 0) {
      foreach ($productReviews as $review) {
        $review->delete();
      }
    }

    // delete user image
    @unlink(public_path('assets/img/users/') . $user->image);

    $user->delete();

    return redirect()->back()->with('success', 'User deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $user = User::query()->find($id);

      // delete all the equipment bookings of this user
      $bookings = $user->equipmentBooking()->get();

      if (count($bookings) > 0) {
        foreach ($bookings as $booking) {
          @unlink(public_path('assets/file/attachments/equipment/') . $booking->attachment);
          @unlink(public_path('assets/file/invoices/equipment/') . $booking->invoice);

          $booking->delete();
        }
      }

      // delete all the equipment reviews of this user
      $equipmentReviews = $user->equipmentReview()->get();

      if (count($equipmentReviews) > 0) {
        foreach ($equipmentReviews as $review) {
          $review->delete();
        }
      }

      // delete all the product orders of this user
      $orders = $user->productOrder()->get();

      if (count($orders) > 0) {
        foreach ($orders as $order) {
          @unlink(public_path('assets/file/attachments/product/') . $order->attachment);
          @unlink(public_path('assets/file/invoices/product/') . $order->invoice);

          // delete all the purchased items of this order
          $items = $order->item()->get();

          foreach ($items as $item) {
            $item->delete();
          }

          $order->delete();
        }
      }

      // delete all the product reviews of this user
      $productReviews = $user->productReview()->get();

      if (count($productReviews) > 0) {
        foreach ($productReviews as $review) {
          $review->delete();
        }
      }

      // delete user image
      @unlink(public_path('assets/img/users/') . $user->image);

      $user->delete();
    }

    Session::flash('success', 'Users deleted successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  //secrtet login
  public function secret_login($id)
  {
    Session::put('secret_login', true);
    $user = User::where('id', $id)->first();
    Auth::guard('web')->login($user);
    return redirect()->route('user.dashboard');
  }
  
  public function create()
  {
      $vendors = Vendor::where('status',1)->get();
      return view('backend.end-user.user.create',compact('vendors'));
  }
  
  public function store(Request $request)
  {
       // validation start
        request()->validate([
          'username' => 'required|unique:users|max:255',
          'email' => 'required|email:rfc,dns|unique:users|max:255',
          'password' => 'required|confirmed',
          'password_confirmation' => 'required',
          'first_name' => 'required',
            'last_name' => 'required',
        ]);

       

        $in = $request->all();
        
        $in['password'] = Hash::make($request->password);
        $in['status'] = 1;
        
        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/img/users/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);
            $in['image'] = $fileName;
        }
        
        $in['owner_id'] = auth()->user()->id;
        
        $user = new User();
        $user->first_name = $in['first_name'];
        $user->last_name = $in['last_name'];
        $user->username = $in['username'];
        $user->email = $in['email'];
        $user->password = $in['password'];
        $user->status = $in['status'];
        $user->contact_number = $in['contact_number'];
        $user->image = $in['image']??'';
        $user->city = $in['city'];
        $user->state = $in['state'];
        $user->country = $in['country'];
        $user->address = $in['address'];
        $user->owner_id = $in['owner_id'];
        $user->vendor_id = $in['vendor_id'];
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->save();
        //$user = User::create($in);

        // pushing to invoice system 
        
        $response = Http::post(env('INVOICE_SYSTEM_URL').'api/users', [
            'first_name' => $in['first_name'],
            'last_name' => $in['last_name'],
            'email' => $in['email'],
            'password' => $request->password,
            'contact' => $in['contact_number'],
            'postal_code' => '-',
            'address' => $in['address'],
            'website' => '',
            'vendor_email' => auth()->user()->email
        ]);
  
        $jsonData = $response->json();
        $msg = '';
        if($response->successful()){
            $msg = 'And Customer Pushed to Invoice System Successfully!';
        }
        Session::flash('success', 'Add Customer Successfully!'.' '.$msg);
        
        // $company = Company::where('customer_id',$in['owner_id'])->first();
        // $mailData['subject'] = 'Welcome to '. $company->name .'!';
        // $mailData['body'] = "We're pleased to inform you that your account as the Branch Manager at  ". $company->name ." has been successfully created.<br/><br/> Please use the following credentials to access your account:<br/><ul><li>Email: ".$in['email']."</li><li>".$request->password."</li></ul> ". $company->name ."<br/><br/>Upon login, you'll be prompted to set up your account details.<br/>. We're excited to have you join our team and look forward to your valuable contributions.<br/><br/>Best regards,<br/>".auth()->user()->username."";
        // $mailData['recipient'] = $request->email;
        // BasicMailer::sendMail($mailData);
        
        
        
        // Admin Email
        $admin = Admin::find(1);
        $mailData['subject'] = 'New Manager Added';
        $mailData['body'] = "New ". $user->email ." User added successfully ";
        $mailData['recipient'] = $admin->email;
        BasicMailer::sendMail($mailData);
        
        return redirect()->route('admin.user_management.registered_users')->with('status' , 'success');
  }
  
  public function change_account_type($id)
  {
      $user = User::find($id);
      $company = Company::where('customer_id',$user->id)->first();
      return view('backend.end-user.user.change_account_type',compact('user','company')); 
  }
  public function update_account_type(Request $request,$id)
  {
    $user = User::find($id);
    if($request->account_type == 'indivisual_account')
    {
        $user->account_type = 'indivisual_account';
        $user->owner_id = null;
        
        $find_company = Company::where('customer_id',$user->id)->first();
        if($find_company)
        {
            $find_company->delete();
        }
    }
    else if($request->account_type == 'corperate_account')
    {
        $request->validate([
            'company_name' => 'required'
            ]);
        $user->account_type = 'corperate_account';
        
        $find_company = Company::where('customer_id',$user->id)->first();
        
        if($find_company)
        {
            $find_company->name = $request->company_name;
            $find_company->update();
        }else{
            $company = new Company();
            $company->name = $request->company_name;
            $company->customer_id = $user->id;
            $company->save();
        }
        
    }
    $user->update();
    return redirect()->route('admin.user_management.registered_users')->with('success','User Account Type Updated Successfully');
  }
  
  
  public function assign_to_vendor($id)
  {
      $user = User::find($id);
      $vendors = Vendor::where('status',1)->get();
      return view('backend.end-user.user.assign_to_vendor',compact('user','vendors')); 
  }
  
  public function update_assign_to_vendor(Request $request,$id)
  {
      $user = User::find($id);
      $user->vendor_id = $request->vendor_id ?? null;
      $user->update();
      return redirect()->route('admin.user_management.registered_users')->with('success','User Updated Successfully');
  }
  
  
  // corporate account
  public function corporate_accounts(Request $request)
  {
      $searchKey = null;

    if ($request->filled('info')) {
      $searchKey = $request['info'];
    }
    $users = User::query()->where('account_type','corperate_account')->when($searchKey, function ($query, $searchKey) {
      return $query->where('username', 'like', '%' . $searchKey . '%')
        ->orWhere('email', 'like', '%' . $searchKey . '%');
    })
      ->orderByDesc('id')
      ->paginate(10);

    return view('backend.end-user.user.corporate_accounts', compact('users'));
  }
  
  
  // branches
  public function branches(Request $request)
  {
     $searchKey = null;

    if ($request->filled('info')) {
      $searchKey = $request['info'];
    }
    
    $branches = CompanyBranch::query()->when($searchKey, function ($query, $searchKey) {
      return $query->where('name', 'like', '%' . $searchKey . '%');
    })
      ->orderByDesc('id')->paginate(10);

    return view('backend.end-user.user.branch', compact('branches'));
  }
  
  public function managers(Request $request)
  {
      $managers = User::where('owner_id','!=',null)->paginate(10);
      return view('backend.end-user.user.managers', compact('managers'));
  }
  
}
