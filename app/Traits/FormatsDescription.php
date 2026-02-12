<?php

namespace App\Traits;

trait FormatsDescription
{
    /**
     * Shared logic to convert URLs into clickable links.
     */
    protected function formatUrlContent($content)
    {
        if (empty($content)) {
            return $content;
        }

        $urlPattern = '/(https?:\/\/[^\s]+|www\.[^\s]+)/i';
        
        if (preg_match($urlPattern, $content)) {
            $formatted = preg_replace(
                $urlPattern, 
                '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-primary">$1</a>', 
                $content
            );
            return str_replace('href="www.', 'href="http://www.', $formatted);
        }

        return $content;
    }
}
