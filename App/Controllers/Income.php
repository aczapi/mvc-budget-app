<?php

namespace App\Controllers;

use \Core\View;

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
    View::renderTemplate('Income/income.html');
  }
}
