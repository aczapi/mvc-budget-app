<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Incomes;
use \App\Flash;

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

  /**
   * Add income 
   *
   * @return void
   */
  public function addAction()
  {
    $income = new Incomes($_POST);

    if ($income->save()) {
      Flash::addMessage('The income has been added.');
      $this->redirect('/income/index');
    } else {
      View::renderTemplate('Income/income.html', [
        'income' => $income,
        'incomesCategory' => Incomes::getIncomesCategoryAssignToUser()
      ]);
    }
  }
}
