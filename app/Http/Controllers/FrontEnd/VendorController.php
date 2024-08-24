<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\Section;
use App\Models\Instrument\Equipment;
use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Models\VendorReview;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\PHPMailer;

use App\Models\EquipmentFieldsValue; // code by AG

class VendorController extends Controller
{
    //index
    public function index(Request $request)
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $queryResult['pageHeading'] = $misc->getPageHeading($language);

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_vendor_page', 'meta_description_vendor_page')->first();
        $us_name = $location = $rating = null;

        $vendorUsIds = [];
        if ($request->filled('us_name')) {
            $us_name = $request->us_name;
            $vendorInfos = VendorInfo::where(function ($query) use ($us_name) {
                return $query->where('shop_name', 'like', '%' . $us_name . '%');
            })
                ->where('language_id', $language->id)
                ->get();

            foreach ($vendorInfos as $vendorInfo) {
                if (!in_array($vendorInfo->vendor_id, $vendorUsIds)) {
                    array_push($vendorUsIds, $vendorInfo->vendor_id);
                }
            }
        }

        $vendorIds = [];
        if ($request->filled('location')) {
            $location = $request->location;

            $vendorInfos = VendorInfo::where(function ($query) use ($location) {
                return $query->where('country', 'like', '%' . $location . '%')
                    ->orWhere('city', 'like', '%' . $location . '%')
                    ->orWhere('state', 'like', '%' . $location . '%')
                    ->orWhere('zip_code', 'like', '%' . $location . '%')
                    ->orWhere('address', 'like', '%' . $location . '%');
            })
                ->where('language_id', $language->id)
                ->get();

            foreach ($vendorInfos as $vendorInfo) {
                if (!in_array($vendorInfo->vendor_id, $vendorIds)) {
                    array_push($vendorIds, $vendorInfo->vendor_id);
                }
            }
        }
        if ($request->filled('rating')) {
            $rating = $request->rating;
        }



        $secInfo = Section::query()->select('subscribe_section_status')->first();
        $queryResult['secInfo'] = $secInfo;



        $queryResult['bgImg'] = $misc->getBreadcrumb();

        $vendors = DB::table('vendors')
            ->join('vendor_infos', 'vendors.id', 'vendor_infos.vendor_id')
            ->when($us_name, function ($query) use ($us_name) {
                return $query->where('vendors.username', 'like', '%' . $us_name . '%')
                    ->orWhere('vendor_infos.shop_name', 'like', '%' . $us_name . '%');
            })
            ->when(
                $location,
                function ($query) use ($vendorIds) {
                    return $query->whereIn('vendors.id', $vendorIds);
                }
            )
            ->when(
                $rating,
                function ($query, $rating) {
                    return $query->where('vendors.avg_rating', '>=', $rating);
                }
            )
            ->select('vendors.*', 'vendor_infos.shop_name')
            ->where('vendor_infos.language_id', $language->id)
            ->paginate(8);

        $queryResult['vendors'] = $vendors;

        return view('frontend.vendor.index', $queryResult);
    }
    //details 
    public function details(Request $request)
    {
        try {
            $misc = new MiscellaneousController();

            $language = $misc->getLanguage();

            $queryResult['bgImg'] = $misc->getBreadcrumb();
            $vendor = Vendor::with([
                'vendor_info' => function ($query) use ($language) {
                    return $query->where('language_id', $language->id);
                }
            ])->where('username', $request->username)->first();

            $queryResult['vendor'] = $vendor;

            $secInfo = Section::query()->select('subscribe_section_status')->first();
            $queryResult['secInfo'] = $secInfo;

            $allEquipment = Equipment::query()->where('vendor_id', $vendor->id)->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
                ->where('equipment_contents.language_id', '=', $language->id)

                ->select('equipments.id', 'equipments.thumbnail_image', 'equipments.lowest_price', 'equipment_contents.title', 'equipment_contents.slug', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipment_contents.features', 'equipments.offer', 'equipment_contents.equipment_category_id')
                ->orderBy('equipments.id', 'desc')
                ->paginate(3);

            $allEquipment->map(function ($equipment) {
                $avgRating = $equipment->review()->avg('rating');
                $ratingCount = $equipment->review()->count();

                $equipment['avgRating'] = floatval($avgRating);
                $equipment['ratingCount'] = $ratingCount;
                
                // code by AG start
                $equipment_fields = EquipmentFieldsValue::where('equipment_id', $equipment->id)->first();
            
                if($equipment_fields){
                    $information['equipment_fields'] = json_decode($equipment_fields->fields_value, true);
                    $information['multiple_charges_settings'] = json_decode($equipment_fields->multiple_charges_settings, true);
                }
                else{
                    $information['equipment_fields'] = array();
                    $information['multiple_charges_settings'] = array();
                }
                
                $equipment['multiple_charges_settings'] = $information['multiple_charges_settings'];
                
                // code by AG end
            });

            $user = Auth::guard('web')->user();
            if ($user) {
                $user_id = $user->id;
            } else {
                $user_id = NULL;
            }

            $reviews = $vendor->reviews()->where('user_id', '!=', $user_id)->paginate(2);
            $review = $vendor->reviews()->where('user_id', $user_id)->first();

            $queryResult['reviews'] = $reviews;
            $queryResult['review'] = $review;
            $queryResult['allEquipments'] = $allEquipment;
            $queryResult['currencyInfo'] = $this->getCurrencyInfo();
            $queryResult['info'] = Basic::select('google_recaptcha_status')->first();

            $queryResult['categories'] = $language->equipmentCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();
            return view('frontend.vendor.details', $queryResult); //code...
        } catch (\Exception $th) {
            return view('errors.404');
        }
    }

    public function review(Request $request)
    {
        if ($request->rating || $request->comment) {
            if (VendorReview::where('user_id', Auth::guard('web')->user()->id)->where('vendor_id', $request->vendor_id)->exists()) {
                $exists =    VendorReview::where('user_id', Auth::guard('web')->user()->id)->where('vendor_id', $request->vendor_id)->first();
                if ($request->rating) {
                    $exists->update([
                        'rating' => $request->rating,
                    ]);
                    $avg_rating = VendorReview::where('vendor_id', $request->vendor_id)->avg('rating');
                    Vendor::find($request->vendor_id)->update([
                        'avg_rating' => $avg_rating
                    ]);
                }
                if ($request->comment) {
                    $exists->update([
                        'comment' => $request->comment,
                    ]);
                }
                Session::flash('success', 'Review update successfully');
                return back();
            } else {
                $input = $request->all();
                $input['user_id'] = Auth::guard('web')->user()->id;
                $data = new VendorReview();
                $data->create($input);
                $avgreview = VendorReview::where('vendor_id', $request->vendor_id)->avg('rating');
                Vendor::find($request->vendor_id)->update([
                    'avg_rating' => $avgreview
                ]);
                Session::flash('success', 'Review submit successfully');
                return back();
            }
        } else {
            Session::flash('error', 'Review  not submit');
            return back();
        }
    }
    //contact
    public function contact(Request $request)
    {
        $info = Basic::select('google_recaptcha_status')->first();
        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => $info->google_recaptcha_status == 1 ? 'required|captcha' : ''
        ];

        $messageArray = [];

        if ($info->google_recaptcha_status == 1) {
            $messageArray['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
            $messageArray['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
        }

        $validator = Validator::make($request->all(), $rules, $messageArray);
        if ($validator->fails()) {
            Session::flash('error', $validator->errors()->first());
            return back();
        }


        $be = Basic::select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')->firstOrFail();
        if ($be->smtp_status == 1) {
            $subject = $request->subject;
            $msg = "
                    <h4>Name : $request->name</h4>
                    <h4>Email : $request->email</h4>
                    <p>Message : $request->message</p>
                    ";

            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = $be->smtp_host;                    // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = $be->smtp_username;                     // SMTP username
                $mail->Password   = $be->smtp_password;                               // SMTP password
                $mail->SMTPSecure = $be->encryption;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = $be->smtp_port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($request->vendor_email);     // Add a recipient

                // Content
                $mail->isHTML(true);
                $mail->Subject =  $subject;
                $mail->Body    = $msg;
                $mail->send();
                Session::flash('success', 'Message sent successfully');
                return back();
            } catch (Exception $e) {
                Session::flash('error', $e);
                return back();
            }
        }
    }
}
