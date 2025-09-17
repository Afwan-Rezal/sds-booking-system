<?php

namespace App\Http\Requests\room;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
        return [
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
            'number_of_people' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            // 'room_id.required' => 'Room ID is required',
            // 'room_id.exists' => 'Selected room does not exist',
            'date.required' => 'Date is required',
            'date.date' => 'Invalid date format',
            'date.after_or_equal' => 'Date must be today or in the future',
            'time_slot.required' => 'Time slot is required',
            'time_slot.string' => 'Invalid time slot format',
            'number_of_people.required' => 'Number of people is required',
            'number_of_people.integer' => 'Number of people must be an integer',
            'number_of_people.min' => 'At least one person is required for booking', // To edit
        ];
    }
}
