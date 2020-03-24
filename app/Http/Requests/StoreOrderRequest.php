<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'shopping_cart_id' => 'required|numeric',
            'recipient_name' => 'required|string|max:50',
            'address' => 'required|string|max:80',
            'country' => 'required|string|max:20',
            'state' => 'required|string|max:20',
            'credit_card' => 'required|string|max:19',
            'expiration_month' => 'required|string|max:2',
            'expiration_year' => 'required|string|max:4',
            'verification_number' => 'required|string|max:3',
        ];
    }
}
