<?php

namespace App\Http\Requests\EmployeesRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeProfileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        if($this->employee->hasRole("employee") && !$this->employee->is_supervisor)
        {
            $rules['nb_of_days'] = ['required'];
            $rules['confessionnels'] = ['required'];
        }
        $rules['first_name'] = ['required'];
        $rules['last_name'] = ['required'];
        $rules['email'] = ['required', 'email', Rule::unique('employees', 'email')->ignore($this->employee)];
        $rules['phone_number'] = ['required', Rule::unique('employees', 'phone_number')->ignore($this->employee)];

        return $rules;
    }
}
