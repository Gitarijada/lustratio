<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    //in order to have different name of table, then pluralise of model name
    protected $table = 'parties';  
    protected $primaryKey = 'id'; 

    protected $fillable = ['name', 'abbreviation'];

    public function valetudinarian()
    {
        return $this->belongsTo(Valetudinarian::class);
    }
    
}
