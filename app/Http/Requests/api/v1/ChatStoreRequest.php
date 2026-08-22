<?php

namespace App\Http\Requests\api\v1;

use App\Enums\ChatType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChatStoreRequest extends FormRequest
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
            'chatName' => ['required' , 'string' , 'regex:/^@chat_/' , Rule::unique('chats' , 'chat_name')],
            'displayName' => ['string' , 'nullable'],
            'type' => ['required' , Rule::in(ChatType::cases())],
            'participants' => ['required' , 'array'],
            'participants.*' => ['required' , 'numeric' ,'distinct' ,'exists:users,id']
        ];
    }
}
