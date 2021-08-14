<?php

namespace App;

use DateTime;

/**
 * Dates
 * 
 */

class Date
{

  public static function getTodayDate()
  {
    $today = new DateTime();
    $today->format('d/m/Y');
    return $today;
  }

  public static function getCurrentMonth()
  {
    return date('m');
  }

  public static function getCurrentYear()
  {
    return date('Y');
  }
  public static function getPreviousMonth()
  {
    return date("m", strtotime("-1 month"));
  }
}
