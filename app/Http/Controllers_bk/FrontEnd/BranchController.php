<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\UserProfileRequest;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Shop\Product;
use App\Models\Shop\ProductOrder;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\BranchUser;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Illuminate\Support\Facades\Response;
use App\Models\UserCard; // code by AG
use App\Models\AdditionalInvoice; // code by AG
use App\Models\BookingUpdate; // code by AG
use App\Models\BookingDriver; // code by AG
use App\Models\EquipmentFieldsValue; // code by AG 
use App\Models\Instrument\EquipmentCategory; // code by AG
use App\Models\Vendor;
use App\Http\Controllers\FrontEnd\PaymentGateway\StaxController; // code by AG
use App\Notifications\BasicNotify; // code by AG
use App\Models\Admin;
use Http;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
        
public function branch()
{
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();
    
    $company = Company::where('customer_id', auth()->user()->id)->first();
    
    if ($company !== null) {
        $queryResult['branches'] = CompanyBranch::where('company_id', $company->id)->get();
    } else {
        // Handle the case where $company is null, perhaps by providing a default value or showing an error message
        $queryResult['branches'] = [];
    }
      $queryResult['managers'] = User::where('owner_id',auth()->user()->id)->get();

    return view('frontend.user.branch.index', $queryResult);
}

  public function branch_create()
  {
      $misc = new MiscellaneousController();
      $queryResult['bgImg'] = $misc->getBreadcrumb();
      $queryResult['managers'] = User::where('owner_id',auth()->user()->id)->get();
      $queryResult['company'] = Company::where('customer_id',auth()->user()->id)->first();
      return view('frontend.user.branch.create',$queryResult);
  }
public function branch_store(Request $request)
{
    $company = Company::where('customer_id', auth()->user()->id)->first();

    if ($company === null) {
        return redirect()->back()->with('error', 'Company not found.');
    }

    $rules = [
        'name' => 'required',
        'location' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator->errors());
    }

    $branch = new CompanyBranch();
    $branch->name = $request->name;
    $branch->location = $request->location;
    $branch->lat = $request->lat;
    $branch->lng = $request->lng;
    $branch->purpose = $request->purpose;
    $branch->significance = $request->significance;
    $branch->name_of_memeber = $request->name_of_memeber;
    $branch->responsibilities = $request->responsibilities;
    $branch->company_id = $company->id;
    $branch->owner_id = auth()->user()->id;
    $branch->save();
    
    // Customer Email



    $mailData['subject'] = 'New Branch Added to Project Repository';
    $mailData['body'] = "Dear Team,<br><br>";
    $mailData['body'] .= "I hope this email finds you well. I wanted to inform you that a new branch has been added to our project repository. The details are as follows: <br><br>";
    $mailData['body'] .= "Branch Name: " . $branch->name . "<br>";
    $mailData['body'] .= "Purpose: " . $branch->purpose . "<br>";
    $mailData['body'] .= "Creator: " . auth()->user()->username . "<br>";
    $mailData['body'] .= "Date Created: " . $branch->created_at . "<br><br>";
    $mailData['body'] .= "This new branch will be instrumental in " . $branch->significance . "<br>";
    $mailData['body'] .= "Please make sure to review the changes and coordinate accordingly with your tasks. If you have any questions or concerns regarding the new branch, feel free to reach out to ".$branch->name_of_memeber. "<br>";
    $mailData['body'] .= "Thank you for your attention to this matter. <br><br>";
    $mailData['body'] .= "Best regards,<br><br>";
    $mailData['body'] .= auth()->user()->username."<br>";
    $mailData['body'] .= "Owner<br>";
    $mailData['body'] .= auth()->user()->contact_number;


    $mailData['recipient'] = auth()->user()->email;


    BasicMailer::sendMail($mailData);
    
    // Admin Email
      $admin = Admin::find(1);
    $mailData['subject'] = 'New Branch Added';
    $mailData['body'] = "New ". $branch->name ." Branch has been successfully added by ". auth()->user()->username ."";
    $mailData['recipient'] = $admin->email;
    BasicMailer::sendMail($mailData);

    return redirect()->route('user.branch')->with('success', 'Branch Created Successfully...');
}

  public function branch_delete($id)
  {
      $branch = CompanyBranch::find($id);
      $branch->delete();
      
      // Customer Email
      $mailData['subject'] = 'Delete Branch';
      $mailData['body'] = "your ". $branch->name ." deleted successfully";
      $mailData['recipient'] = auth()->user()->email;
      BasicMailer::sendMail($mailData);
      
        // Admin Email
      $admin = Admin::find(1);
        $mailData['subject'] = 'Branch Delete';
        $mailData['body'] =  $branch->name ." Branch has been successfully updated by ". auth()->user()->username ."";
        $mailData['recipient'] = $admin->email;
        BasicMailer::sendMail($mailData);
      return redirect()->back()->with('status' , 'success');
  }
  public function branch_edit($id)
  {
      $misc = new MiscellaneousController();

      $queryResult['bgImg'] = $misc->getBreadcrumb();
      $queryResult['managers'] = User::where('owner_id',auth()->user()->id)->get();
      $queryResult['company'] = Company::where('customer_id',auth()->user()->id)->first();
      $queryResult['branch'] = CompanyBranch::find($id);
      return view('frontend.user.branch.edit',$queryResult);
  }
  public function branch_update(Request $request,$id)
  {
      $company = Company::where('customer_id',auth()->user()->id)->first();
       $rules = [
          'name' => 'required',
          'location' => 'required',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
          return redirect()->back()->withErrors($validator->errors());
        }
      
      $branch = CompanyBranch::find($id);
      $branch->name = $request->name;
      $branch->location = $request->location;
      $branch->company_id = $company->id;
        $branch->lat = $request->lat;
        $branch->lng = $request->lng;
        $branch->purpose = $request->purpose;
        $branch->significance = $request->significance;
        $branch->responsibilities = $request->responsibilities;
      $branch->update();
      // Customer Email
      $mailData['subject'] = 'Updated Branch Detalis';
      $mailData['body'] = "your ". $branch->name ." details updated successfully";
      $mailData['recipient'] = auth()->user()->email;
      BasicMailer::sendMail($mailData);
      
      // Admin Email
      $admin = Admin::find(1);
      $mailData['subject'] = 'Updated Branch Detalis';
      $mailData['body'] =  $branch->name ." Branch has been successfully deleted by ". auth()->user()->username ."";
      $mailData['recipient'] = $admin->email;
      BasicMailer::sendMail($mailData);
      return redirect()->route('user.branch')->with('success','Branch Updated Successfully...');
  }
  
  
  
  public function assign_manager(Request $request)
  {
       $check = BranchUser::where('user_id', $request->user_id)
                       ->where('branch_id', $request->branch_id)
                       ->first();

        if ($check) {
            return redirect()->back()->with('error', 'This manager already assigned to this branch'); 
        }
      
      
      $assign = new BranchUser();
      $assign->branch_id = $request->branch_id;
      $assign->user_id = $request->user_id;
      $assign->role = 2;
      $assign->save();
      
      $manager = User::find($request->user_id);
      $branch = CompanyBranch::find($request->branch_id);
      
        $mailData['subject'] = ' Welcome to the Team!';
        $mailData['body'] = '<br>Dear '.$manager->username. "<br><br>";
        $mailData['body'] .= 'Welcome aboard to our team! We are excited to have you join us and contribute to our collective success. As the newest member of our team, we want to ensure you have everything you need to hit the ground running.<br><br>';
        $mailData['body'] .= 'Here are your login credentials for accessing our systems:<br><br>';
        $mailData['body'] .= 'Email: '.$manager->email.'<br>';
        $mailData['body'] .= 'Temporary Password: '.$manager->temporary_password.'<br><br>';
        $mailData['body'] .= 'Please use this information to log in to our systems at your earliest convenience. Upon logging in, you will be prompted to change your password for security purposes.<br><br>';
        $mailData['body'] .= 'Additionally, we have assigned you to oversee the following branch:.<br><br>';
        
        $mailData['body'] .= 'Branch Name: '.$branch->name.'<br><br>';
        $mailData['body'] .= 'Branch Purpose: '.$branch->purpose.'<br><br>';
        $mailData['body'] .= 'Branch Responsibilities: '.$branch->responsibilities.'<br><br>';
        
        $mailData['body'] .= "Your role in managing this branch is crucial to our project's success, and we trust you will excel in your responsibilities.<br><br>";
        $mailData['body'] .= "If you have any questions or need assistance getting started, please don't hesitate to reach out to me or any member of the team. We're here to support you every step of the way.<br><br>";
        $mailData['body'] .= "Once again, welcome to the team! We look forward to achieving great things together.<br><br>";
        $mailData['body'] .= "Best regards,.<br><br>";
        $mailData['body'] .= auth()->user()->username."<br>";
        $mailData['body'] .= "Owner<br>";
        $mailData['body'] .= auth()->user()->contact_number;
        
        $mailData['recipient'] = $manager->email;
        $mailData['sessionMessage'] = 'Congratulations on Your Appointment as Branch Manager!';
        BasicMailer::sendMail($mailData);
      return redirect()->back()->with('success' , 'Manager successfully assigned to branch');
  }
  
  public function unassign_manager(Request $request)
  {
     
      $manager = BranchUser::find($request->id);
      $manager->delete();
      
    //   $manager_find = User::find($manager->user_id);
    //   $branch = CompanyBranch::find($manager->branch_id);
      
    //     $mailData['subject'] = 'Update: Your Role as Branch Manager';
    //     $mailData['body'] = "Dear " . $manager_find->username . ",<br/><br/>We wanted to inform you that you are no longer assigned as the Branch Manager for ". $branch->name .".<br/><br/>Best regards,<br/>".auth()->user()->username."";
    //     $mailData['recipient'] = $manager_find->email;
    //     $mailData['sessionMessage'] = 'Update: Your Role as Branch Manager';
    //     BasicMailer::sendMail($mailData);
        
        
      return redirect()->back()->with('success' , 'Manager successfully unassigned from the branch.');
  }
      public function get_manager(Request $request)
      {
        $manager_ids = BranchUser::where('branch_id',$request->branch_id)->get()->pluck('user_id');
        $manager_user_ids = BranchUser::where('branch_id',$request->branch_id)->get()->pluck('id');
        $manager = User::WhereIn('id',$manager_ids)->get();
        return response()->json(['manager'=>$manager , 'manager_ids' => $manager_user_ids]);
      }
  
}