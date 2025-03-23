<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'max:80', 'required'],
            'description' => ['string', 'required'],
            'status' => ['string', 'in:pending,completed,in progress', 'required'],
        ];
    }
}
