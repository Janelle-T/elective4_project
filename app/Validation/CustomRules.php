<?php

namespace App\Validation;

class CustomRules
{
    // Custom validation rule to check if the date format is valid
    public function valid_datetime(string $str, string $fields = null, array $data = null): bool
    {
        // Convert from datetime-local format (YYYY-MM-DDTHH:MM) to Y-m-d H:i:s format
        $str = str_replace('T', ' ', $str) . ':00'; // Add seconds as :00 (defaults to zero)

        // Check if the datetime is in Y-m-d H:i:s format
        $format = 'Y-m-d H:i:s';
        $date = \DateTime::createFromFormat($format, $str);

        // Return true if valid, false otherwise
        return $date && $date->format($format) === $str;
    }
}
