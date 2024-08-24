<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;

use App\Http\Helpers\UploadFile;
use App\Models\User;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Custom\VoximplantController;
use App\Http\Controllers\BackEnd\Custom\OptionController;
use App\Mail\NewCustomerCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Helpers\BasicMailer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $information['allCustomer'] = User::query()->where('vendor_id', Auth::guard('vendor')->user()->id)
            //->orderByDesc('drivers.id')
            ->get();

        return view('vendors.customer.index', $information);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $information = array();
        return view('vendors.customer.create', $information);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation start
        $rules = [
            //   'username' => 'required|unique:users|max:255',
            'email' => 'required|email:rfc,dns|unique:users|max:255',
            //   'password' => 'required|confirmed',
            //   'password_confirmation' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $in = $request->all();

        $passwordString = '@@$785ASC@';
        $in['password'] = Hash::make($passwordString);
        $in['username'] = strtolower($request->first_name . $request->last_name . uniqid());
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

        $in['vendor_id'] = Auth::guard('vendor')->user()->id;

        $user = new User();
        $user->first_name = $in['first_name'];
        $user->last_name = $in['last_name'];
        $user->username = $in['username'];
        $user->email = $in['email'];
        $user->password = $in['password'];
        $user->status = $in['status'];
        $user->contact_number = $in['contact_number'];
        $user->image = $in['image'] ?? '';
        $user->city = $in['city'];
        $user->state = $in['state'];
        $user->country = $in['country'];
        $user->address = $in['address'];
        $user->vendor_id = $in['vendor_id'];
        $user->account_type = "indivisual_account";
        $user->save();
        //$user = User::create($in);

        $mailData['subject'] = 'Welcome to Helly';
        $mailData['body'] = 'Hi '. $request->first_name. ' ' . $request->last_name .',<br><br>';
        $mailData['body'] .= "Customer Details: <br><br>";
        $mailData['body'] .= "<ul><li>Password: ".$passwordString."</li><li>Login here : " . route('user.login') . "</li></ul><br><br>";
        $mailData['body'] .= "Thank you for your attention to this matter.<br><br>";
        $mailData['body'] .= "Best regards,<br>";
        $mailData['body'] .= "Helly";
        $mailData['recipient'] = $request->email;
        BasicMailer::sendMail($mailData);

        // pushing to invoice system 

        $response = Http::post(env('INVOICE_SYSTEM_URL') . 'api/users', [
            'first_name' => $in['first_name'],
            'last_name' => $in['last_name'],
            'email' => $in['email'],
            'password' => $request->password,
            'contact' => $in['contact_number'],
            'postal_code' => '-',
            'address' => $in['address'],
            'website' => '',
            'vendor_email' => Auth::guard('vendor')->user()->email
        ]);

        $jsonData = $response->json();
        $msg = '';
        if ($response->successful()) {
            $msg = 'And Customer Pushed to Invoice System Successfully!';
        }
        Session::flash('success', 'Add Customer Successfully!' . ' ' . $msg);

        return Response::json(['status' => 'success'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $customer = User::find($id);
        if ($customer) {
            if ($customer->vendor_id != Auth::guard('vendor')->user()->id) {
                return redirect()->route('vendor.dashboard');
            }
        } else {
            return redirect()->route('vendor.dashboard');
        }


        $information['customer'] = $customer;

        return view('vendors.customer.edit', $information);
    }

    public function update(Request $request, $id)
    {

        $rules = [
            'username' => [
                'required',
                'not_in:admin',
                Rule::unique('users', 'username')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id)
            ],
            'first_name' => 'required',
            'last_name' => 'required',
        ];

        if ($request->hasFile('photo')) {
            //$rules['photo'] = 'mimes:png,jpeg,jpg|dimensions:min_width=80,max_width=80,min_width=80,min_height=80';
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }


        $in = $request->all();


        $user  = User::find($id);
        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/img/users/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);

            @unlink(public_path('assets/img/users/') . $driver->image);
            $in['image'] = $fileName;
        }


        $user->first_name = $in['first_name'];
        $user->last_name = $in['last_name'];
        // $user->username = $in['username'];
        // $user->email = $in['email'];
        // $user->password = $in['password'];
        // $user->status = $in['status'];
        $user->contact_number = $in['contact_number'];
        if (isset($in['image'])) {
            $user->image = $in['image'];
        }

        $user->city = $in['city'];
        $user->state = $in['state'];
        $user->country = $in['country'];
        $user->address = $in['address'];
        $user->account_type = "indivisual_account";
        // $user->vendor_id = $in['vendor_id'];
        $user->save();

        // pushing to invoice system 

        $response = Http::put(env('INVOICE_SYSTEM_URL') . 'api/users/' . base64_encode($user->email), [
            'first_name' => $in['first_name'],
            'last_name' => $in['last_name'],
            'contact' => $in['contact_number'],
            'postal_code' => '-',
            'address' => $in['address'],
            'website' => '',
            'vendor_email' => Auth::guard('vendor')->user()->email
        ]);

        $jsonData = $response->json();
        $msg = '';
        if ($response->successful()) {
            $msg = 'And Customer Update Pushed to Invoice System Successfully!';
        }

        Session::flash('success', 'Customer updated successfully!' . ' ' . $msg);

        return Response::json(['status' => 'success'], 200);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        #====finally delete the user=======
        @unlink(public_path('assets/img/users/') . $user->image);

        $user->delete();

        return redirect()->back()->with('success', 'Customer deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $user = User::find($id);

            #====finally delete the driver=======
            @unlink(public_path('assets/img/users/') . $user->photo);
            $user->delete();
        }

        Session::flash('success', 'Customers deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    // customer calling method
    public function calling($id)
    {
        $system_calling_service_provider = 'voximplant';


        if ($system_calling_service_provider == 'voximplant') {
            $voximplant = new VoximplantController();
            return $voximplant->customer_calling($id);
        } else if ($system_calling_service_provider == 'messagebird') {
            //return $this->vendor_calling_messagebird($id);
        } else {
            return 'Calling Provider Not Selected';
        }
    }

    // lead sms_communication
    public function sms_communication($id)
    {
        $vendor = User::find($id);

        $options = new OptionController();
        $communication_settings = $options->get_options();
        $system_sms_service_provider = $communication_settings['system_sms_service_provider'] ?? 'voximplant';

        return view('vendors.communication.smschat', compact('vendor', 'system_sms_service_provider'));
    }
}
