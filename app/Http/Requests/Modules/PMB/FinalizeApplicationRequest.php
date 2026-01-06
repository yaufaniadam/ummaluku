<?php

namespace App\Http\Requests\Modules\PMB;

use Illuminate\Foundation\Http\FormRequest;

class FinalizeApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only users with PMB management permission can finalize
        return $this->user()->hasPermission('manage pmb settings') || 
               $this->user()->hasPermission('view applications');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // No additional validation needed - Application model binding handles existence
            // Authorization is checked in authorize() and controller policy
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            // Add custom messages if needed in future
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            // Add custom attributes if needed
        ];
    }
}
