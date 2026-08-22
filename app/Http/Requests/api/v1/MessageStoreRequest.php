<?php

namespace App\Http\Requests\api\v1;

use App\Enums\MessageType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MessageStoreRequest extends FormRequest
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
            'message' => ['required' , 'string'],
            'type' => ['required' , Rule::in(MessageType::cases())],
            'senderId' => ['required' , 'exists:users,id']
        ];
    }
}
