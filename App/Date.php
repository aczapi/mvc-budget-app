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
    // $today = date('m/d/Y');
    $todayDate = new \DateTime();
    $today = $todayDate->format('Y-m-d');
    return $today;
  }

  public static function getYesterdayDate()
  {
    $yesterday = date('Y/m/d', strtotime("-1 days"));
    return $yesterday;
  }

  // // public static function getCurrentMonth()
  // // {
  // //   return date('m');
  // // }

  // public static function getCurrentYear()
  // {
  //   return date('Y');
  // }
  // public static function getPreviousMonth()
  // {
  //   return date("m", strtotime("-1 month"));
  // }
}
