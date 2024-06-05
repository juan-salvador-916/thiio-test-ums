<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateUserDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'ADMIN';
    }

    protected function prepareForValidation() 
    {
        $this->merge(['id' => $this->route('id')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id'=> 'integer|exists:users,id',
            'name' => 'nullable|min:2|string',
            'last_name' => 'nullable|min:2|string',
            'password' => 'nullable|min:8|string',
            'role' => 'nullable|in:ADMIN,NORMAL',
            'email' => 'nullable|email|unique:users'
        ];
    }
}
