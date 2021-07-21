<?php

namespace App\Controllers;

use \Core\View;
use App\Auth;

/**
 * Income controller
 *
 */
class Income extends Authenticated
{
  /**
   * Show the income page
   *
   * @return void
   */
  public function indexAction()
  {
    $this->requireLogin();
    View::renderTemplate('Income/income.html');
  }
}
