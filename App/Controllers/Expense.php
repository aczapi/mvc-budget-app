<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expenses;
use \App\Flash;
use \App\Models\Balances;

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
      'paymentMethods' => Expenses::getPaymentMethodsAssignToUser(),
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
        'paymentMethods' => Expenses::getPaymentMethodsAssignToUser(),
      ]);
    }
  }

  public function updateExpenseAction()
  {

    $expense = new Expenses($_POST);
    // var_dump($expense);

    if ($expense->update()) {
      echo "The expense has been updated.";
    } else echo "The expense could not be edited.";
  }


  public function deleteExpenseAction()
  {

    $expense = new Expenses($_POST);
    // var_dump($expense);

    if ($expense->delete()) {
      echo "The expense has been deleted.";
    } else echo "The expense could not be deleted.";
  }

  // public function deleteExpenseAction()
  // {

  //   $expense = new Expenses($_POST);
  //   // var_dump($expense);

  //   if ($expense->delete()) {
  //     echo "The expense has been deleted.";
  //   } else echo "The expense could not be deleted.";
  // }
}
