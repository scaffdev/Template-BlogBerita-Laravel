<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'content' => ['required', 'string', 'min:3', 'max:2000'],
        ];

        if (! $this->user()) {
            $rules['author_name'] = ['required', 'string', 'max:100'];
            $rules['author_email'] = ['required', 'email', 'max:255'];
        } else {
            $rules['author_name'] = ['nullable', 'string', 'max:100'];
            $rules['author_email'] = ['nullable', 'email', 'max:255'];
        }

        return $rules;
    }
}
