<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model     //MY change for count visits
{
    use HasFactory;

    protected $fillable = ['url', 'ip_address'];
}
