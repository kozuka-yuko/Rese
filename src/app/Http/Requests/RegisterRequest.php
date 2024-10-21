<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique|string|max:191|',
            'password' => 'required|min:8|max:191|confirmed',
            'password_confirmation' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前を入力してください',
            'name.string' => '名前を文字列で入力してください',
            'name.max:191' => '名前は191文字以内で入力して下さい',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => '有効なメールアドレス形式で入力してください',
            'email.unique' => 'このメールアドレスはすでに使用されています',
            'email.string' => '有効な形式で入力してください',
            'email.max:191' => 'マールアドレスを191文字以内で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min:8' => 'パスワードを8文字以上で設定してください',
            'password.max:191' => 'パスワードを191文字以内で設定してください',
            'password.confirmed' => 'パスワードと確認用パスワードが異なります',
            'password_confirmation.required' => '確認用パスワードを入力してください',
        ];
    }
}
