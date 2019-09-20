<?php

namespace App\Http\Requests;

use App\Notification;
use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $notification = Notification::find($this->route('id'));

        $can = $this->user()->can('edit', $notification);

        return $can;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'required|max:255|min:10'
        ];
    }

    public function messages()
    {
        return [
            'text.required' => 'O campo texto é obrigatório',
            'text.min' => 'O campo texto deve ter no mínimo 10 caracteres'
        ];
    }
}
