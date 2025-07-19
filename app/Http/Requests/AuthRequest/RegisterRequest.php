<?php

namespace App\Http\Requests\AuthRequest;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nom' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:dimension_utilisateurs,email',
            'password' => 'required|string|min:4|max:100',
            'ville' => 'nullable|string|max:100',
            'age' => 'nullable|string|max:20',
            'genre' => 'nullable|string|max:20',
            'date_inscription' => 'nullable|date',
        ];
    }
}
