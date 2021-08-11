<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expenses;
use \App\Flash;

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
    View::renderTemplate('Expense/expense.html', [
      'expensesCategory' => Expenses::getExpensesCategoryAssignToUser(),
      'paymentMethods' => Expenses::getPaymentMethodsAssignToUser()
    ]);
  }

  /**
   * Add expense 
   *
   * @return void
   */
  public function addAction()
  {
    $expense = new Expenses($_POST);

    if ($expense->save()) {
      Flash::addMessage('The expense has been added.');
      $this->redirect('/expense/index');
    } else {
      View::renderTemplate('Expense/expense.html', [
        'expense' => $expense,
        'expensesCategory' => Expenses::getExpensesCategoryAssignToUser(),
        'paymentMethods' => Expenses::getPaymentMethodsAssignToUser()
      ]);
    }
  }
}
