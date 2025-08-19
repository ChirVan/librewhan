<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;

class UpdateCategoryRequest extends FormRequest {
    public function authorize(): bool {
        return session()->has('authenticated');
    }
    public function rules(): array {
        $routeParam = $this->route('category');
        // Route-model binding provides a Category instance; extract its id for the unique rule
        $id = $routeParam instanceof Category ? $routeParam->id : $routeParam;
        return [
            'name' => 'required|string|max:120',
            'slug' => 'nullable|string|max:150|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:80',
            'color' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
            'featured' => 'sometimes|boolean',
            'display_order' => 'nullable|integer|min:0'
        ];
    }
}
