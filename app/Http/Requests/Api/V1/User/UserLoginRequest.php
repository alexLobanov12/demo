<?php

namespace App\Http\Requests\Api\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserLoginRequest extends FormRequest
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
        return [
            'email'      => ['required', 'email'],
            'password'   => ['required', 'min:6', 'max:14'],
        ];
    }

    public function messages()
    {
        return [
            'email.required'      => 'Поле email является обязатьным',
            'email.email'         => 'Введите корректный email-адрес',
            'password.required'   => 'Пароль обязатьное поле',
            'password.min'        => 'Минимальная длина пароля должна составлять 6 символов',
            'password.max'        => 'Максимальная длина пароля должна составлять 14 символов',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'error_messages' => $validator->errors(),
        ], 400));
    }
}
