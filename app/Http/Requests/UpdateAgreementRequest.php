<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAgreementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'company_name' => 'nullable|string|max:255',
            'service_description' => 'required|string',
            'scope_of_work' => 'required|string',
            'price' => 'required|numeric|min:0',
            'payment_terms' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }
}
