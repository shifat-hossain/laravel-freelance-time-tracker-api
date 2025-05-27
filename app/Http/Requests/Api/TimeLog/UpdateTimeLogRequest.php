<?php

namespace App\Http\Requests\Api\TimeLog;

use App\Http\Requests\Api\TimeLog\Concerns\TimeLogRequest;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTimeLogRequest extends TimeLogRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('Unauthorized access.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), []);
    }

    public function prepareForValidation(): void
    {
        $actual_start_at = Carbon::parse($this->start_time);
        $actual_end_at   = Carbon::parse($this->end_time);
        $this->merge([
            'hours' => $actual_end_at->diffInMinutes($actual_start_at, true)/60,
        ]);
    }
}
