<?php

namespace App\Controllers;

use \Core\View;

/**
 * Expense controller
 *
 */
class Expense extends Authenticated
{
  /**
   * Show the expense page
   *
   * @return void
   */
  public function indexAction()
  {
    View::renderTemplate('Expense/expense.html');
  }
}
