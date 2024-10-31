<?php
class Filter
{
  public static function filterData($value, $type)
  {
    switch ($type) {
      case 'name':
        return filter_var(trim($value), FILTER_SANITIZE_SPECIAL_CHARS);
      case 'email':
        return filter_var(trim($value), FILTER_VALIDATE_EMAIL);
      case 'url':
        return filter_var(trim($value), FILTER_VALIDATE_URL);
      case 'text':
        return filter_var(trim($value), FILTER_SANITIZE_SPECIAL_CHARS);
      default:
        return filter_var(trim($value), FILTER_SANITIZE_SPECIAL_CHARS);
    }
  }
}
