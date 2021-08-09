<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expenses;

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
}
