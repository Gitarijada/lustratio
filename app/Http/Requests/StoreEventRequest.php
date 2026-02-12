<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Change to true so the request is allowed
        return true; 
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required',
            'event_name' => 'required|unique:events|max:150',
            'description' => 'required',
        ];
    }

    /*public function messages(): array
    {
        return [
            'date_of_birth.unique' => 'The combination of first name, last name, and date of birth already exist. Please, check existing data for the person you wanna add (if the other person exists and is not the same as your person, please add the number next to the first name. Like "Marko 2" or "Marko II")',
        ];
    }*/

}
