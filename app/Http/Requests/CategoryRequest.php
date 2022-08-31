<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->only([
            'name' => 'required|string',
            'description' => 'require|string',
        ]);
    }

    public function attributes()
    {
        return [
            'name' => '分类名',
            'description' => '描述',
        ];
    }
}
