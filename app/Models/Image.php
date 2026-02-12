<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    //in order to have different name of table, then pluralise of model name
    protected $table = 'images';  
    protected $primaryKey = 'id'; 

    protected $fillable = ['valetudinarian_id', 'image_name'];

    public function valetudinarian()
    {
        // Second argument is the foreign key on the images table
        // Third argument is the owner key on the valetudinarians table
        return $this->belongsTo(Valetudinarian::class, 'valetudinarian_id', 'id');
    }
}
