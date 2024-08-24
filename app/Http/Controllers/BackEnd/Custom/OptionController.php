<?php

namespace App\Http\Controllers\BackEnd\Custom;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPMailer\PHPMailer\PHPMailer;

use App\Models\Option;

class OptionController extends Controller
{
    //
    public function index(){
        $options = $this->get_options();
        
        return view('backend.end-user.option.index', compact('options'));
    }
    
    public function update(Request $request){
        $in = $request->all();
        
        if(!empty($in)){
            foreach($in as $key => $value){
                if($key == '_token'){
                    continue;
                }
                $option = Option::where('key', $key)->first();
                if(!empty($option)){
                    $option->value = $value;
                    $option->save();
                }else{
                    $option = new Option();
                    $option->key = $key;
                    $option->value = $value;
                    $option->save();
                }
            }
        }
        Session::flash('success', 'Communication Settings Updated Successfully!');
        return Response::json(['status' => 'success'], 200);
    }
    
    public function get_options(){
        $options = Option::get()->toArray();
        $keys = array_column($options, 'key');
        $values = array_column($options, 'value');
        $options_array = array_combine($keys,$values);
        return $options_array;
    }
}
