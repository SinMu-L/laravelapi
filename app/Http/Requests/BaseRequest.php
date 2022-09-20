<?php

namespace App\Http\Requests;

use App\Helpers\CustomJsonResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class BaseRequest extends FormRequest
{
    use CustomJsonResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function failedValidation( Validator $validator)
    {
        $message = $validator->errors()->first();

        throw  new HttpResponseException($this->failed($message,201));
    }
}
