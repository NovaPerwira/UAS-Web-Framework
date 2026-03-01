<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignAgreementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // We'll manage authorization with signed URL logic
    }

    public function rules(): array
    {
        return [
            'signature' => 'required|string', // Base64 image
        ];
    }
}
