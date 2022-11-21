<?php

namespace App\Http\Requests\LeaveRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
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
        if($this->leave_type_id == 1)
        {
            $rules['attachment_path'] = ['required'];
        }
        $rules['leave_duration_id'] = ['required'];
        $rules['from'] = ['required', 'date'];
        $rules['to'] = ['required', 'date'];
        $rules['travelling'] = ['required'];
        $rules['leave_type_id'] = ['required'];

        return $rules;
    }
}
