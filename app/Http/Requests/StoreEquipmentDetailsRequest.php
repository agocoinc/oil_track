<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEquipmentDetailsRequest extends FormRequest
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
            'equipment_category_id'       => 'required|exists:equipment_categories,id',
            'loc_name'       => 'nullable|string|max:150',
            'details_aname'  => ['required', 'string', 'max:300', 'regex:/^[\p{Arabic}0-9\x{0660}-\x{0669}\s\p{P}\p{S}]+$/u'],
            'details_lname'  => ['required', 'string', 'max:300', 'regex:/^[a-zA-Z0-9\s\p{P}\p{S}]+$/u'],
            'details_qty'    => 'nullable|integer|min:0',
            'date_from'      => 'nullable|date',
            'date_to'        => 'nullable|date|after_or_equal:equip_date_from',
            'note'           => 'nullable|string|max:300',
        ];
    }

    public function messages()
    {
        return [
            'equipment_category_id.required'            => 'معرف الفئة مطلوب.',
            'equipment_category_id.exists'              => 'معرف الفئة غير موجود.',

            'loc_name.string'        => 'اسم الموقع غير صالح.',
            'loc_name.max'           => 'اسم الموقع يجب ألا يزيد عن 150 حرفاً.',

            'details_aname.required' => 'الاسم العربي مطلوب.',
            'details_aname.string'   => 'الاسم العربي يجب أن يكون نصاً.',
            'details_aname.max'      => 'الاسم العربي يجب ألا يزيد عن 300 حرف.',
            'details_aname.regex'    => 'الاسم العربي يجب أن يحتوي على حروف عربية فقط.',

            'details_lname.required' => 'الاسم اللاتيني مطلوب.',
            'details_lname.string'   => 'الاسم اللاتيني يجب أن يكون نصاً.',
            'details_lname.max'      => 'الاسم اللاتيني يجب ألا يزيد عن 300 حرف.',
            'details_lname.regex'    => 'الاسم اللاتيني يجب أن يحتوي على حروف لاتينية فقط.',

            'details_qty.integer'    => 'الكمية يجب أن تكون رقماً صحيحاً.',
            'details_qty.min'        => 'الكمية لا يمكن أن تكون أقل من صفر.',

            'date_from.date'         => 'تاريخ البداية غير صالح.',

            'date_to.date'           => 'تاريخ النهاية غير صالح.',
            'date_to.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',

            'note.string'            => 'الملاحظة غير صالحة.',
            'note.max'               => 'الملاحظة يجب ألا تزيد عن 300 حرف.',
        ];
    }
}
