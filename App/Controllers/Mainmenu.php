<?php

namespace App\Controllers;

use \Core\View;
use App\Auth;

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
    if (!Auth::isLoggedIn()) {
      Auth::rememberRequestedPage();
      $this->redirect('/login');
    }
    View::renderTemplate('MainMenu/index.html');
  }
}
