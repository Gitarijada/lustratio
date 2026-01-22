<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Valetudinarian extends Model
{
    use HasFactory;
	
	protected $table = 'valetudinarians';   
  
    protected $primaryKey = 'id';   
          
    protected $fillable = ['first_name', 'last_name'];

	public function location()
  	{
    return $this->hasOne(Location::class);
  	}

	public function party()
  	{
    return $this->hasOne(Party::class);
  	}

	// Accessor: This runs automatically when you call $valetudinarian->description
    //public function getDescriptionAttribute($value)
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
