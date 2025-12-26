<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Valetudinarian extends Model
{
    use HasFactory;
	
	protected $table = 'valetudinarians';   
  
    protected $primaryKey = 'id';   
          
    protected $fillable = ['first_name', 'last_name'];

	public function location()
  	{
    return $this->hasOne(Location::class);
  	}

	public function party()
  	{
    return $this->hasOne(Party::class);
  	}

}
