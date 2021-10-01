<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balances;
use \App\Models\Incomes;
use \App\Models\Expenses;
use App\Flash;


class Balance extends Authenticated
{
  public function showAction()
  {
    if (isset($_POST['time-period'])) {
      View::renderTemplate('Balance/show-balance.html', [
        'startDate' => Balances::getStartDate(),
        'endDate' => Balances::getEndDate(),
        'expensesCategory' => Expenses::getExpensesCategoryAssignToUser(),
        'paymentMethods' => Expenses::getPaymentMethodsAssignToUser(),
        'incomesCategory' => Incomes::getIncomesCategoryAssignToUser(),
        'individualExpenses' => Balances::getIndividualExpenses(),
        'individualIncomes' => Balances::getIndividualIncomes()
      ]);
    }
  }
  public function getExpensesSumAction()
  {
    $expenseSum = new Balances();

    return $expenseSum->getSumOfAllExpensesByCategory();
  }

  public function getIncomesSumAction()
  {
    $incomeSum = new Balances();

    return $incomeSum->getSumOfAllIncomesByCategory();
  }
}
