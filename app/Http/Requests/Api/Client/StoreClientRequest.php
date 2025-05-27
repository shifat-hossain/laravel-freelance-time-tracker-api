<?php

namespace App\Http\Requests\Api\Client;

use App\Http\Requests\Api\Client\Concerns\ClientRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends ClientRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules() + [
            'user_id' => 'required',
        ]);
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => $this->user()->id,
        ]);
    }
}
