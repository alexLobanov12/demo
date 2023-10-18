<?php

namespace App\Http\Requests\Api\V1\Article;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArticleStoreRequest extends FormRequest
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
            'title' => ['required', 'max:350'],
            'content' => ['required'],
            'themes_id'  => ['required'],
            'main_image' => ['sometimes'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Введите заголовок',
            'title.max'      => 'Максимальная длина заголовка должна составлять 350 символов',
            'content'        => 'Введите контент статьи',
            'themes_id.required' => 'У статьи должна быть хотя бы одна тема',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'error_messages' => $validator->errors(),
        ], 400));
    }
}
