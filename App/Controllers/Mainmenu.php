<?php

namespace App\Controllers;

use \Core\View;

/**
 * Main menu controller
 *
 */
class MainMenu extends \Core\Controller
{
  /**
   * Show the main-menu page
   *
   * @return void
   */
  public function indexAction()
  {
    View::renderTemplate('MainMenu/main-menu.html');
  }
}
