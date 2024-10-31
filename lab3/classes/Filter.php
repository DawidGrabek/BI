<?php
class Filter
{
  public static function filterData($value, $type)
  {
    switch ($type) {
      case 'name':
        // Remove special HTML characters for display but preserve text
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
      case 'email':
        // Validate email
        return filter_var(trim($value), FILTER_VALIDATE_EMAIL);
      case 'url':
        // Validate URL
        return filter_var(trim($value), FILTER_VALIDATE_URL);
      case 'text':
        // Use htmlspecialchars for general text to encode special characters
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
      default:
        // Default to htmlspecialchars if type is unspecified
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
  }
}
