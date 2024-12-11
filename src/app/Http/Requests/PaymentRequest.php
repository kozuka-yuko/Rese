<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'amount'=> 'required|numeric|min:1'
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => '金額を入力してください',
            'amount.numeric' => '数値を入力してください',
            'amount.min:1' => '1円以上で金額を指定してください',
        ];
    }
}
