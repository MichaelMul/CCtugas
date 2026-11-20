<?php

namespace App\Http\Requests;

use App\Models\Registration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $fullName = $this->input('full_name');
        $email = $this->input('email');

        $this->merge([
            'full_name' => is_string($fullName) ? trim($fullName) : $fullName,
            'email' => is_string($email) ? Str::lower(trim($email)) : $email,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', Rule::unique(Registration::class, 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'date_of_birth' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already registered.',
            'password.confirmed' => 'The password confirmation does not match.',
            'date_of_birth.before_or_equal' => 'The date of birth cannot be in the future.',
        ];
    }
}
