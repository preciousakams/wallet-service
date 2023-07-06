<?php

namespace App\Http\Requests\User\Referral;

use Illuminate\Foundation\Http\FormRequest;

class SendInvitationRequest extends FormRequest
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
            'email' => 'required|email:filter|unique:auth_service.users,email',
            'callbackUrl' => 'required'
        ];
    }

    public function messages()
    {
        return [
          'email.unique' => 'This email is already registered'
        ];
    }
}
