<?php

namespace App\Http\Requests\Api\Client;

use App\Http\Requests\Api\Client\Concerns\ClientRequest;
use App\Models\Client;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends ClientRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->id == $this->client->user_id;
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('Unauthorized data access.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'email' => Rule::unique('clients')->ignore($this->client),
        ]);
    }
}
