<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValeEvent extends Model
{
    use HasFactory;

    protected $table = 'vale_events';   
  
    protected $primaryKey = 'id';   
          
    protected $fillable = [
        'valetudinarian_id', 
        'event_id',
    ];   
}
