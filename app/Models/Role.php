<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
//use App\Models\Role as Role;

class Role extends Eloquent {

	public function users ()
    {
        return $this->belongsTo('App\Models\User');
    }

}
