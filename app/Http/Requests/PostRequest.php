<?php

namespace App\Http\Requests;

use App\Post;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!$this->route('id'))
            return true;
        else {
            $post = Post::find($this->route('id'));

            $can = $this->user()->can('edit', $post);

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
            'text' => 'required|max:255|min:20'
        ];
    }

    public function messages()
    {
        return [
            'text.required' => 'O campo texto é obrigatório',
            'text.min' => 'O campo texto deve ter no mínimo 20 caracteres'
        ];
    }
}
