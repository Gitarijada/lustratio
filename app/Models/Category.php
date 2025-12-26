<?php 

namespace App\Models;

//use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {

	use HasFactory;

	//in order to have different name of table, then pluralise of model name
	protected $table = 'categories';
	protected $primaryKey = 'id'; 

	protected $fillable = ['category_name'];
	
	public function event() 
	{
		return $this->belongsToMany(Event::class);
	}

}
