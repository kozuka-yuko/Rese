<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopReviewRequest extends FormRequest
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
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'stars.required' => '評価を選んでください',
            'stars.integer' => '評価を正しく選んでください',
            'stars.min:1' => '評価を☆1個以上で選んでください',
            'stars.max:5' => '評価を☆5個以内で選んでください',
            'comment.required' => 'コメントを必ず入力してください',
        ];
    }
}
