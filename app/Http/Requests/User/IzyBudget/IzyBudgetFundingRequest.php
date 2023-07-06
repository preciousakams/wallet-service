<?php

namespace App\Http\Requests\User\IzyBudget;

use Illuminate\Foundation\Http\FormRequest;

class IzyBudgetFundingRequest extends FormRequest
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
            'plan_id' => 'required|exists:izybudget_plan,id'
        ];
    }
}
