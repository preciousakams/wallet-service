<?php

namespace App\Http\Requests\Administrator\IzyPay;

use Illuminate\Foundation\Http\FormRequest;

class DepositUserRequest extends FormRequest
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
            'email' => 'required|email:filter|exists:auth_service.users,email',
            'amount' => 'required|numeric'
        ];
    }
}
