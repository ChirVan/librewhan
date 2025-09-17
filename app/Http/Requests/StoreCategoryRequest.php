<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCategoryRequest extends FormRequest {
    public function authorize(): bool {
        // Using custom session-based auth (auth middleware)
        return session()->has('authenticated');
    }
    public function rules(): array {
        return [
            'name'=>'required|string|max:120',
            'slug'=>'nullable|string|max:150|unique:categories,slug',
            'description'=>'nullable|string',
            'icon'=>'nullable|string|max:80',
            'color'=>'nullable|string|max:20',
            'status'=>'required|in:active,inactive',
            'featured'=>'sometimes|boolean',
            'display_order'=>'nullable|integer|min:0'
        ];
    }
}
