<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Requests\MailFromUserRequest;
use App\Models\BasicSettings\Basic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Http;

class ContactController extends Controller
{
	public function contact()
	{
		$misc = new MiscellaneousController();

		$language = $misc->getLanguage();

		$queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_contact', 'meta_description_contact')->first();

		$queryResult['pageHeading'] = $misc->getPageHeading($language);

		$queryResult['bgImg'] = $misc->getBreadcrumb();

		$queryResult['info'] = Basic::select('email_address', 'contact_number', 'address', 'google_recaptcha_status')->firstOrFail();

		return view('frontend.contact', $queryResult);
	}

	public function sendMail(MailFromUserRequest $request)
	{
		$info_ = Basic::select('google_recaptcha_status')->firstOrFail();
		if ($info_->google_recaptcha_status == 1) {
            $request->validate([
                'g-recaptcha-response' => 'required|captcha'
            ]);
    
            // Verify reCAPTCHA
            $response = $request->input('g-recaptcha-response');
            $secretKey = config('recaptcha.RECAPTCHA_SECRET_KEY');
            $url = 'https://www.google.com/recaptcha/api/siteverify';
    
            $response = Http::asForm()->post($url, [
                'secret' => $secretKey,
                'response' => $response,
            ]);
    
            $body = $response->json();
            if (!($body['success'] && $body['score'] >= 0.5)) {
                return redirect()->back()->with('error', 'reCAPTCHA validation failed. Please try again.');
            }
        }
    		$info = Basic::select('to_mail')->firstOrFail();
    
    		$from = $request->email;
    		$name = $request->name;
    		$to = $info->to_mail;
    		$subject = $request->subject;
    		$message = $request->message;
    
    		$mail = new PHPMailer(true);
    		$mail->CharSet = 'UTF-8';
    		$mail->Encoding = 'base64';
    
    		try {
    			$mail->setFrom($from, $name);
    			$mail->addAddress($to);
    
    			$mail->isHTML(true);
    			$mail->Subject = $subject;
    			$mail->Body = $message;
    
    			$mail->send();
    
    			Session::flash('success', 'Mail has been sent.');
    		} catch (Exception $e) {
    			Session::flash('error', 'Mail could not be sent!');
    		}
    
    		return redirect()->back();
	}
}
