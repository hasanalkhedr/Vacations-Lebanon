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
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', Rule::unique('employees', 'email')->ignore($this->employee)],
            'phone_number' => ['required', Rule::unique('employees', 'phone_number')->ignore($this->employee)],
            'nb_of_days' => ['required'],
            'department_id' => ['required'],
            'role_id' => ['required']
        ];
    }
}
