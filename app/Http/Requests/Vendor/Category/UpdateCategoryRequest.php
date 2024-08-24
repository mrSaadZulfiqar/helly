<?php

namespace App\Http\Requests\Vendor\Category;

use App\Models\Invoice\Category;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rule = Category::$rules;
        $rule['name'] = 'required|max:191';

        return $rule;
    }
}
