<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;

class UniquePerson implements Rule
{
    public function passes($attribute, $value)
    {
        return !User::where('first_name', request('first_name'))
            ->where('last_name', request('last_name'))
            ->where('date_of_birth', request('date_of_birth'))
            ->exists();
    }

    public function message()
    {
        return 'The combination of first name, last name, and date of birth already exists.';
    }
}
