<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUser extends FormRequest
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
        $method = $this->method();
        if ($method == 'PUT') {
            return [
                'name' => 'required|min:5',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'type' => ['required', Rule::in(['i', 'b', 'B', 'I'])],
                'address' => 'required',
                'city' => 'required',
            ];
        } else {
            return [
                'name' => 'nullable|min:5',
                'email' => 'nullable|email|unique:users',
                'password' => 'nullable|min:8',
                'type' => ['nullable', Rule::in(['i', 'b', 'B', 'I'])],
                'address' => 'nullable',
                'city' => 'nullable',
            ];
        }
    }
}
