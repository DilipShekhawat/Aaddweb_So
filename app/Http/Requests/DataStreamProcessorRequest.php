<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DataStreamProcessorRequest extends FormRequest
{
    public function rules()
    {
        return [
            'stream' => 'required|string',
            'k' => 'required|integer|min:1',
            'top' => 'required|integer|min:1',
            'exclude' => 'nullable|array',
            'exclude.*' => 'string'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}