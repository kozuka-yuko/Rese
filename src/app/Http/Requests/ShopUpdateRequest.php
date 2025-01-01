<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopUpdateRequest extends FormRequest
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
            'name' => 'required|max:100',
            'area' => 'required',
            'genre' => 'required',
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '店舗の名前を入力してください',
            'name.max:100' => '100文字以下で入力してください',
            'area.required' => 'エリアを選択してください',
            'genre.required' => 'ジャンルを選択してください',
            'description.required' => '店舗情報を入力してください',
        ];
    }
}
