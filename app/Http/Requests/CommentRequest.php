<?php

namespace App\Http\Requests;

use App\Comment;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->method() == 'POST') {
            return true;
        } else {
            $comment = Comment::find($this->route('id'));

            $can = $this->user()->can('edit', $comment);

            return $can;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'required|max:255|min:5'
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'O campo descrição é obrigatório',
            'description.min' => 'O campo descrição deve ter no mínimo 5 caracteres'
        ];
    }
}
