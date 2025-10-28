<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'user_role' => 'required|in:student,staff',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username is required',
            'email.required' => 'Email is required',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Passwords do not match',
            'full_name.required' => 'Full name is required',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Please select a valid gender',
            'user_role.required' => 'Please select your role',
            'user_role.in' => 'Please select a valid role',
        ];
    }
}
