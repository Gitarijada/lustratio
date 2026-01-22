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
    
    protected $appends = ['formatted_description'];
    
    // Accessor: This runs automatically when you call $event->description
    //public function getDescriptionAttribute($value) { if (empty($value)) return $value;...return $value;}
    // This runs automatically when you call $valetudinarian->formatted_description, returns the processed value
    public function getFormattedDescriptionAttribute() // Removed $value parameter
    {
        // Get the raw value from the 'description' column
        $rawValue = $this->attributes['description'] ?? null; 
        
        if (empty($rawValue)) {
            return $rawValue;
        }

        $urlPattern = '/(https?:\/\/[^\s]+|www\.[^\s]+)/i';
        
        if (preg_match($urlPattern, $rawValue)) {
            // Use the rawValue in the regex replacement
            $formattedValue = preg_replace($urlPattern, '<a href="$1" target="_blank" class="text-primary">$1</a>', $rawValue);
            return str_replace('href="www.', 'href="http://www.', $formattedValue);
        }

        return $rawValue;
    }
}
