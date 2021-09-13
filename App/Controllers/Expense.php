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
    var_dump($expense);

    if ($expense->update()) {
      echo "The expense has been updated.";
    } else echo "The expense could not be edited.";

    //   if ($expense->update()) {
    //     Flash::addMessage('The expense has been updated.');
    //     $this->redirect('/balance/show');
    //   } else {
    //     Flash::addMessage('The expense could not be edited.', Flash::WARNING);
    //     View::renderTemplate('Balance/show-balance.html', [
    //       'startDate' => Balances::getStartDate(),
    //       'endDate' => Balances::getEndDate(),
    //       'expensesCategory' => Expenses::getExpensesCategoryAssignToUser(),  //update??
    //       'paymentMethods' => Expenses::getPaymentMethodsAssignToUser(),     //update??
    //       'sumExpensesByCategories' => Balances::getSumOfAllExpensesByCategory(),
    //       'sumIncomesByCategories' => Balances::getSumOfAllIncomesByCategory(),
    //       'individualExpenses' => Balances::getIndividualExpenses(),
    //       'individualIncomes' => Balances::getIndividualIncomes()
    //     ]);
    //   }
  }
}
