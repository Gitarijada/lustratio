<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Valetudinarian;

class UniquePerson implements Rule
{
    // in UniquePerson rule
    protected $ignoreId;

    public function __construct($ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    public function passes($attribute, $value)
    {
        /*return ! Valetudinarian::where('first_name', request('first_name'))
            ->where('last_name', request('last_name'))
            ->where('date_of_birth', request('date_of_birth'))
            ->exists();*/

        //modify the rule to update (ignore current record). Need to allow updating the same record (ignore the same user's id)
        $qury = Valetudinarian::where('first_name', request('first_name'))
        ->where('last_name', request('last_name'))
        ->where('date_of_birth', request('date_of_birth'));

        if ($this->ignoreId) {
            $qury->where('id', '!=', $this->ignoreId);
        }

        return ! $qury->exists();
    }

    public function message()
    {
        return 'The combination of first name, last name, and date of birth already exist. Please, check existing data for the person you wanna add (if, the other person exist and is not the same as your person, please, add the number next to the first name. Like "Marko 2" or "Marko II")';
    }
}
