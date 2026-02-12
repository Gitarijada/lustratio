<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreValetudinarianRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Change to true so the request is allowed
        return true; 
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:35',   //|unique:valetudinarians',
            'last_name' => 'required|string|max:35',
            'sobriquet' => 'max:35',
            'location_id' => 'required',
            'party_id' => 'required',
            'phone' => 'max:20',
                //'val_description' => 'nullable|string|max:700',
                //'date_of_birth' => [new UniquePerson],
                // custom rule:
            'date_of_birth' => [
            'nullable',
            'date',
            Rule::unique('valetudinarians')
                ->where('first_name', $this->first_name)
                ->where('last_name', $this->last_name)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'date_of_birth.unique' => 'The combination of first name, last name, and date of birth already exist. Please, check existing data for the person you wanna add (if the other person exists and is not the same as your person, please add the number next to the first name. Like "Marko 2" or "Marko II")',
        ];
    }

}
