<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Withdraw;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Transcation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;

class WithdrawController extends Controller
{
    //index
    public function index()
    {
        $search = request()->input('search');

        $collection = Withdraw::with('method')
            ->when($search, function ($query, $keyword) {
                return $query->where('withdraws.withdraw_id', 'like', '%' . $keyword . '%');
            })
            ->orderBy('id', 'desc')->paginate(10);
        return view('backend.withdraw.history.index', compact('collection'));
    }
    //delete
    public function delete(Request $request)
    {
        $delete = Withdraw::findOrFail($request->id);
        $delete->delete();
        return redirect()->back()->with('success', 'Withdraw Request Deleted Successfully!');
    }
    //approve
    public function approve($id)
    {
        $withdraw = Withdraw::findOrFail($id);

        //mail sending
        // get the website title & mail's smtp information from db
        $info = Basic::select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name', 'base_currency_symbol_position', 'base_currency_symbol')
            ->first();

        //preparing mail info
        // get the mail template info from db
        $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'withdraw_approve')->first();
        $mailData['subject'] = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        // get the website title info from db
        $website_info = Basic::select('website_title')->first();

        $vendor = $withdraw->vendor()->first();

        // preparing dynamic data
        $vendorName = $vendor->username;
        $vendorEmail = $vendor->email;
        $vendor_amount = $vendor->amount;
        $withdraw_amount = $withdraw->amount;
        $total_charge = $withdraw->total_charge;
        $payable_amount = $withdraw->payable_amount;

        $method = $withdraw->method()->select('name')->first();

        $websiteTitle = $website_info->website_title;

        // replacing with actual data
        $mailBody = str_replace('{vendor_username}', $vendorName, $mailBody);
        $mailBody = str_replace('{withdraw_id}', $withdraw->withdraw_id, $mailBody);

        $mailBody = str_replace('{current_balance}', $info->base_currency_symbol . $vendor_amount, $mailBody);
        $mailBody = str_replace('{withdraw_amount}', $info->base_currency_symbol . $withdraw_amount, $mailBody);
        $mailBody = str_replace('{charge}', $info->base_currency_symbol . $total_charge, $mailBody);
        $mailBody = str_replace('{payable_amount}', $info->base_currency_symbol . $payable_amount, $mailBody);

        $mailBody = str_replace('{withdraw_method}', $method->name, $mailBody);
        $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

        $mailData['body'] = $mailBody;

        $mailData['recipient'] = $vendorEmail;
        //preparing mail info end

        // initialize a new mail
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // if smtp status == 1, then set some value for PHPMailer
        if ($info->smtp_status == 1) {
            $mail->isSMTP();
            $mail->Host       = $info->smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $info->smtp_username;
            $mail->Password   = $info->smtp_password;

            if ($info->encryption == 'TLS') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Port       = $info->smtp_port;
        }

        // add other informations and send the mail
        try {
            $mail->setFrom($info->from_mail, $info->from_name);
            $mail->addAddress($mailData['recipient']);

            $mail->isHTML(true);
            $mail->Subject = $mailData['subject'];
            $mail->Body = $mailData['body'];

            $mail->send();
            $withdraw->status = 1;

            $transcation = Transcation::where('booking_id', $withdraw->id)->where('transcation_type', 2)->first();
            if ($transcation) {
                $transcation->update(['payment_status' => 1]);
            }

            Session::flash('success', 'Withdraw Request Approved Successfully!');
        } catch (Exception $e) {
            Session::flash('warning', 'Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }
        //mail sending end
        $withdraw->save();
        return redirect()->back();
    }
    //decline
    public function decline($id)
    {
        $withdraw = Withdraw::findOrFail($id);

        //mail sending
        // get the website title & mail's smtp information from db
        $info = Basic::select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name', 'base_currency_symbol_position', 'base_currency_symbol')
            ->first();

        //preparing mail info
        // get the mail template info from db
        $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'withdraw_rejected')->first();
        $mailData['subject'] = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        // get the website title info from db
        $website_info = Basic::select('website_title')->first();

        $vendor = $withdraw->vendor()->first();

        // preparing dynamic data
        $vendorName = $vendor->username;
        $vendorEmail = $vendor->email;
        $vendor_amount = $vendor->amount + $withdraw->amount;

        $method = $withdraw->method()->select('name')->first();

        $websiteTitle = $website_info->website_title;

        // replacing with actual data
        $mailBody = str_replace('{vendor_username}', $vendorName, $mailBody);
        $mailBody = str_replace('{withdraw_id}', $withdraw->withdraw_id, $mailBody);

        $mailBody = str_replace('{current_balance}', $info->base_currency_symbol . $vendor_amount, $mailBody);
        $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

        $mailData['body'] = $mailBody;

        $mailData['recipient'] = $vendorEmail;
        //preparing mail info end

        // initialize a new mail
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // if smtp status == 1, then set some value for PHPMailer
        if ($info->smtp_status == 1) {
            $mail->isSMTP();
            $mail->Host       = $info->smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $info->smtp_username;
            $mail->Password   = $info->smtp_password;

            if ($info->encryption == 'TLS') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Port       = $info->smtp_port;
        }

        // add other informations and send the mail
        try {
            $mail->setFrom($info->from_mail, $info->from_name);
            $mail->addAddress($mailData['recipient']);

            $mail->isHTML(true);
            $mail->Subject = $mailData['subject'];
            $mail->Body = $mailData['body'];

            $mail->send();
            $vendor = Vendor::findOrFail($withdraw->vendor_id);
            $vendor->amount = ($vendor->amount + ($withdraw->amount));
            $vendor->save();

            $transcation = Transcation::where('booking_id', $withdraw->id)->first();
            if ($transcation) {
                $transcation->update(['payment_status' => 2]);
            }

            $withdraw->status = 2;
            Session::flash('success', 'Withdraw request decline & balance return to vendor account successfully!');
        } catch (Exception $e) {
            Session::flash('warning', 'Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }
        //mail sending end


        $withdraw->save();
        return redirect()->back();
    }
}
