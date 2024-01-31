<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => [
                'required',
                'date',
                'after_or_equal:now', // 過去の日時ではないことを確認
            ],
            'time' => 'required|date_format:H:i', // 時刻のフォーマットは必要に応じて変更してください
            'people' => 'required|integer|between:1,10',
        ];
    }
    public function messages()
    {
        return [
            'date.after_or_equal' => '予約は過去、及び当日の日時にはできません。当日のご予約は、お店に直接お問い合わせください。',
        ];
    }
}