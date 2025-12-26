<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageEvent extends Model
{
    use HasFactory;

    //in order to have different name of table, then pluralise of model name
    protected $table = 'images_events';  
    protected $primaryKey = 'id'; 

    protected $fillable = ['event_id', 'image_name'];
}
