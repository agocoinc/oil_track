<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEquipmentCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'aname' => ['required', 'string', 'max:300', 'regex:/^[\p{Arabic}0-9\x{0660}-\x{0669}\s\p{P}\p{S}]+$/u'],
            'lname' => ['required', 'string', 'max:300', 'regex:/^[a-zA-Z0-9\s\p{P}\p{S}]+$/u'],
            'note'  => 'nullable|string|max:300',
        ];
    }

    public function messages()
    {
        return [
            'aname.required' => 'الاسم العربي مطلوب.',
            'aname.string'   => 'الاسم العربي يجب أن يكون نصاً.',
            'aname.max'      => 'الاسم العربي يجب ألا يزيد عن 300 حرف.',
            'aname.regex'    => 'الاسم العربي يجب أن يحتوي على حروف عربية فقط.',

            'lname.required' => 'الاسم اللاتيني مطلوب.',
            'lname.string'   => 'الاسم اللاتيني يجب أن يكون نصاً.',
            'lname.max'      => 'الاسم اللاتيني يجب ألا يزيد عن 300 حرف.',
            'lname.regex'    => 'الاسم اللاتيني يجب أن يحتوي على حروف لاتينية فقط.',

            'note.string'    => 'ملاحظة غير صالحة.',
            'note.max'       => 'الملاحظة يجب ألا تزيد عن 300 حرف.',
        ];
    }
}
