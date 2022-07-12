<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['string', 'required', 'unique:products'],
            'price' => ['numeric', 'between:0.00,99.99', 'required'],
            'description' => ['string', 'required'],
            'category' => ['string', 'required'],
            'image_url' => ['string']
        ];
    }
}
