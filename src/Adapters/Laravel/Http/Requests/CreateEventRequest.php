<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Pan\Enums\EventType;

/**
 * @internal
 */
final class CreateEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|string>>
     */
    public function rules(): array
    {
        return [
            'events' => ['required', 'array'],
            'events.*.blueprint' => ['required', 'string', 'max:255'],
            'events.*.type' => ['required', Rule::enum(EventType::class)],
        ];
    }
}
