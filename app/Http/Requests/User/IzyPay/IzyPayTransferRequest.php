<?php

namespace App\Http\Requests\User\IzyPay;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class IzyPayTransferRequest extends FormRequest
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

    public function prepareForValidation()
    {
        return $this->merge([
            'sender_liveizy' => Auth::user()->liveizy_id
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_liveizy' => 'required|exists:auth_service.users,liveizy_id',
            'sender_liveizy' => 'different:user_liveizy',
            'amount' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'user_liveizy.exists' => 'Invalid user id',
            'sender_liveizy.different' => 'You can\'t transfer to yourself'
        ];
    }
}
