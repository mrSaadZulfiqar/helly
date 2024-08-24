<?php

namespace App\Http\Requests\Instrument;

use App\Models\BasicSettings\Basic;
use App\Rules\IsEquipmentAvailableRule;
use Illuminate\Foundation\Http\FormRequest;

class BookingProcessRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $shippingInfo = Basic::select('self_pickup_status', 'two_way_delivery_status')->first();
    $selfPickupStatus = $shippingInfo->self_pickup_status;
    $twoWayDeliveryStatus = $shippingInfo->two_way_delivery_status;

    return [
      'dates' => [
        'required',
        new IsEquipmentAvailableRule($this->equipment_id)
      ],
      'name' => 'required',
      'contact_number' => 'required',
      'email' => 'required|email:rfc,dns',
      'shipping_method' => $selfPickupStatus == 1 || $twoWayDeliveryStatus == 1 ? 'required' : '',
      //'location' => 'required|numeric', // commented by AG
      'price_message' => 'sometimes|required',
      
      'accept_terms_conditions' => 'required'
    ];
  }

  /**
   * Get the validation messages that apply to the request.
   *
   * @return array
   */
  public function messages()
  {
    return [
      'price_message.required' => 'The message field is required.'
    ];
  }
}
