<?php

namespace App\Http\Requests\api\v1;

use App\Enums\ChatType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChatUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'displayName' => ['sometimes' , 'string'],
            'chatName' => ['sometimes' , 'string' , 'regex:/^@chat_/' , Rule::unique('chats' , 'chat_name')]
        ];
    }
}
