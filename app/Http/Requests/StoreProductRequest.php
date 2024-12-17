<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'price' => ['required', 'integer'],
            //'category_id' => 'required|exists:categories,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
