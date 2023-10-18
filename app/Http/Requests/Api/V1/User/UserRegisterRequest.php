<?php

namespace App\Http\Requests\Api\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegisterRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:10'],
            'last_name'  => ['sometimes', 'string', 'max:20'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'min:6', 'max:14'],
            'role_id'    => ['required']
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Имя обязатьное поле',
            'first_name.max'      => 'Масимальная длина имени не должна привышать 10 симвонолов',
            'last_name.max'       => 'Масимальная длина фамилии не должна привышать 20 симвонолов',
            'email.required'      => 'Поле email является обязатьным',
            'email.email'         => 'Введите корректный email-адрес',
            'email.unique'        => 'Пользователь с данным адресом уже существует',
            'password.required'   => 'Пароль обязатьное поле',
            'password.min'        => 'Минимальная длина пароля должна составлять 6 символов',
            'password.max'        => 'Максимальная длина пароля должна составлять 14 символов',
            'role_id.required'    => 'Роль обязательное поле',
        ];
    }
    
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'error_messages' => $validator->errors(),
        ], 400));
    }
}
