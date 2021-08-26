<?php

namespace App\Controllers;

use \Core\View;
use \App\Date;

/**
 * Main menu controller
 *
 */
class MainMenu extends Authenticated
{

  /**
   * Show the main-menu page
   *
   * @return void
   */
  public function indexAction()
  {
    View::renderTemplate('MainMenu/index.html', [
      'today' => Date::getTodayDate(),
      'yesterday' => Date::getYesterdayDate()
    ]);
  }
}
