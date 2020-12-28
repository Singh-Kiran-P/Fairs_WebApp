<?php

/**
 * Prevent XSS injections
 *
 * @param [type] $string
 * @return void
 */
function _e($string)
{
  return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
