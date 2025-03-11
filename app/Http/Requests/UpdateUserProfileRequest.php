<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255'],
            'tgl_lahir' => 'required|date|before:today',
            'no_telp' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'tinggi_badan' => 'required|numeric',
            'berat_badan' => 'required|numeric',
            'tingkat_aktivitas' => 'required|string|in:sedentary,lightly_active,moderately_active ,very_active,extra_active',
        ];
    }
}
