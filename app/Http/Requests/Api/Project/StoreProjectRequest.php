<?php

namespace App\Http\Requests\Api\Project;

use App\Http\Requests\Api\Project\Concerns\ProjectRequest;
use App\Models\Client;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends ProjectRequest
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
        return array_merge(parent::rules(), [
            'client_id' => 'exists:clients,id',
            'status' => 'required|string|in:active,completed',
        ]);
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'status' => 'active',
        ]);
    }
}
