<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        if (app()->environment(['local', 'development'])) {
            parent::failedValidation($validator);
        }

        throw new HttpResponseException(response()->json([
            'message' => 'The submitted data is invalid.',
        ], 422));
    }
}