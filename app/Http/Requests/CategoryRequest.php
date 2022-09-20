<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class CategoryRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // https://laravel.com/docs/5.3/validation#rule-required-without
        // 验证两个字段必须有一个必填
        // 龟龟，不能有空格
        return [
            'name' => 'required_without:description',
            'description' => 'required_without:name',
        ];

    }

    public function attributes()
    {
        return [
            'name' => '分类名',
            'description' => '描述',
        ];
    }

    public function messages()
    {
        return [
            'name.required_without' => 'name 和 description 必须有一个必填',
            'description.required_without' => 'name 和 description 必须有一个必填',
        ];
    }

    // 验证器的后置函数
    protected function passedValidation()
    {

        $error_key_array = $this->except(array_keys($this->rules()));
        if (!empty($error_key_array)) {

            $message = key($error_key_array) . ' 非法';
            throw  new HttpResponseException($this->failed($message));
        }

    }

    // 验证器的前置函数
    protected function prepareForValidation()
    {
        $error_key_array = $this->except(array_keys($this->rules()));
        if (!empty($error_key_array)) {

            $message = key($error_key_array) . ' 非法';
            throw  new HttpResponseException($this->failed($message));
        }
    }

}
