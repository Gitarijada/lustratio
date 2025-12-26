<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Correction extends Model
{
    use HasFactory;
	
	protected $table = 'valetudinarians_corr';   
  
    protected $primaryKey = 'id';   
          
    protected $fillable = ['valetudinarian_id', 'owner_id'];

}
