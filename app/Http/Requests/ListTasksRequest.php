<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListTasksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['string', 'in:pending,completed,in progress', 'sometimes'],
            'created_at' => ['date', 'sometimes'],
            'per_page' => ['integer', 'nullable', 'min:1', 'max:20'],
        ];
    }
}
