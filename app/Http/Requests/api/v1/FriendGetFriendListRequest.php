<?php

namespace App\Http\Requests\api\v1;

use App\Enums\FriendType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FriendGetFriendListRequest extends FormRequest
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
            'type' => ['required'  , Rule::in(['all' , ...collect(FriendType::cases())->pluck('value')->all()])]
        ];
    }
}
