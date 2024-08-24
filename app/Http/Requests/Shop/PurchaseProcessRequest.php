<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseProcessRequest extends FormRequest
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
    return [
      'billing_first_name' => 'required',
      'billing_last_name' => 'required',
      'billing_email' => 'required|email:rfc,dns',
      'billing_contact_number' => 'required',
      'billing_address' => 'required',
      'billing_city' => 'required',
      'billing_country' => 'required',
      'shipping_first_name' => 'required',
      'shipping_last_name' => 'required',
      'shipping_email' => 'required|email:rfc,dns',
      'shipping_contact_number' => 'required',
      'shipping_address' => 'required',
      'shipping_city' => 'required',
      'shipping_country' => 'required'
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
      'billing_first_name.required' => 'The first name field is required.',
      'billing_last_name.required' => 'The last name field is required.',
      'billing_email.required' => 'The email field is required.',
      'billing_contact_number.required' => 'The phone number field is required.',
      'billing_address.required' => 'The address field is required.',
      'billing_city.required' => 'The city field is required.',
      'billing_country.required' => 'The country field is required.',
      'shipping_first_name.required' => 'The first name field is required.',
      'shipping_last_name.required' => 'The last name field is required.',
      'shipping_email.required' => 'The email field is required.',
      'shipping_contact_number.required' => 'The phone number field is required.',
      'shipping_address.required' => 'The address field is required.',
      'shipping_city.required' => 'The city field is required.',
      'shipping_country.required' => 'The country field is required.'
    ];
  }
}
