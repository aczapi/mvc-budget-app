<?php

namespace App\Controllers;

use \Core\View;
use App\Auth;

/**
 * Income controller
 *
 */
class Income extends \Core\Controller
{
  /**
   * Show the income page
   *
   * @return void
   */
  public function indexAction()
  {
    if (!Auth::isLoggedIn()) {
      Auth::rememberRequestedPage();
      $this->redirect('/login');
    }
    View::renderTemplate('Income/income.html');
  }
}
