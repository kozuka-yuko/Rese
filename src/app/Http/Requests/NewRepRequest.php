<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewRepRequest extends FormRequest
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
            'shop_name' => 'required',
            'shop_rep_name' => 'required|max:100',
            'phone_number' => 'required|min:10|max:20',
        ];
    }

    public function messages()
    {
        return [
            'shop_name.required' => '名前を入力してください',
            'shop_rep_name.required' => '名前を入力してください',
            'shop_rep_name.max:100' => '100文字以下で入力してください',
            'phone_number.required' => '電話番号を入力してください',
            'phone_number.min:10' => '電話番号を10桁以上入力してください',
            'phone_number.max:100' => '電話番号は20桁以内で入力してください'
        ];
    }
}
