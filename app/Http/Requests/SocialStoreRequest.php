<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SocialStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'text' => 'required|string',
            'sector' => 'required|exists:sectors,id',
            'author_id' => 'sometimes|exists:users,id',
            'tag_id' => 'sometimes|exists:tags,id',
            'post_id' => 'sometimes|exists:posts,id',
        ];
    }
}
