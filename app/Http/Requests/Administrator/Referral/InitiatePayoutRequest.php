<?php

namespace App\Http\Requests\Administrator\Referral;

use Illuminate\Foundation\Http\FormRequest;

class InitiatePayoutRequest extends FormRequest
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
            'referral_id' => 'required|exists:auth_service.users,id',
            'amount' => 'required|numeric',
            'remarks' => 'required',
            'gaps_username' => 'required',
            'gaps_accesscode' => 'required',
            'gaps_password' => 'required',
        ];
    }
}
