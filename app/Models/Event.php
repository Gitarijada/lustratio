<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';   
  
    protected $primaryKey = 'id'; 
    
    protected $fillable = [
        'event_name',
        'description',
        'event_date',
        'location_id',
        'category_id',
        'owner_id'
    ];    
}
