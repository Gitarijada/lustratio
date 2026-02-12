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
        'owner_id',
        'vev_description',
    ]; 
    
    protected $appends = ['formatted_vev_description'];
    // Accessor: This runs automatically when you call $valetudinarian->description
    //public function getDescriptionAttribute($value)
    // This runs automatically when you call $valetudinarian->formatted_description, returns the processed value
    public function getFormattedVevDescriptionAttribute() // Removed $value parameter
    {
        // Get the raw value from the 'description' column
        $rawValue = $this->attributes['vev_description'] ?? null; 
        
        if (empty($rawValue)) {
            return $rawValue;
        }

        $urlPattern = '/(https?:\/\/[^\s]+|www\.[^\s]+)/i';
        
        if (preg_match($urlPattern, $rawValue)) {
            // Use the rawValue in the regex replacement
            $formattedValue = preg_replace($urlPattern, '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-primary">$1</a>', $rawValue);
            return str_replace('href="www.', 'href="http://www.', $formattedValue);
        }

        return $rawValue;
    }
}
