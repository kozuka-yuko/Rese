<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepUpdateRequest extends FormRequest
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
            'shop_rep_name' => 'required|max:100',
            'email' => 'required|string|email|unique:users|max:191',
        ];
    }

    public function messages()
    {
        return [
            'shop_rep_name.required' => '代表者の名前を入力してください',
            'shop_rep_name.max:100' => '100文字以下で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.string' => 'メールアドレスを正しく入力してください',
            'email.email' => '有効なメールアドレス形式で入力してください',
            'email.unique' => 'このメールアドレスはすでに使用されています',
            'email.max:191' => 'メールアドレスを191文字以内で入力してください',
        ];
    }
}
