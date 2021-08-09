<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Incomes;

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
    View::renderTemplate('Income/income.html', [
      'incomesCategory' => Incomes::getIncomesCategoryAssignToUser()
    ]);
  }
}
