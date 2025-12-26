<?php 

namespace App\Models;

//use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model {

	public $timestamps = false; // Disable automatic timestamps, not use without `updated_at` & `created_at`
        // ...
	use HasFactory;

	//in order to have different name of table, then pluralise of model name
	protected $table = 'locations';
	protected $primaryKey = 'id'; 

	protected $fillable = ['region', 'city_zip', 'city', 'zip', 'name'];

}
