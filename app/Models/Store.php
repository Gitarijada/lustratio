<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    //in order to have different name of table, then pluralise of model name
    protected $table = 'stories';  
    protected $primaryKey = 'id'; 

    protected $fillable = ['name', 'abbreviation'];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
    
}
