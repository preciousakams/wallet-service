<?php

namespace App\Http\Requests\User\Referral;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReferralAccountRequest extends FormRequest
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
            'bank_id' => 'required|exists:banks,id',
            'account_number' => ['required','min:7','string',
                Rule::unique('bank_details')->ignore($this->user()->id,'user_id')
            ],
            'account_name' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'account_number.unique' => 'This account number has been used, Pls contact the Administrator if you think something is wrong'
        ];
    }
}
