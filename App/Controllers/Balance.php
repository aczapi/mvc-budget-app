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
        'expensesCategory' => Expenses::getExpensesCategoryAssignToUser(),  //update??
        'paymentMethods' => Expenses::getPaymentMethodsAssignToUser(),
        'incomesCategory' => Incomes::getIncomesCategoryAssignToUser(),  //update??
        'sumExpensesByCategories' => Balances::getSumOfAllExpensesByCategory(),
        'sumIncomesByCategories' => Balances::getSumOfAllIncomesByCategory(),
        'individualExpenses' => Balances::getIndividualExpenses(),
        'individualIncomes' => Balances::getIndividualIncomes()
      ]);
    }
  }
  public function updateExpensesSumAction()
  {
    $expenseSum = new Balances();

    $expenseSum->updateExpenseSum();
  }

  public function updateIncomesSumAction()
  {
    $incomeSum = new Balances();

    $incomeSum->updateIncomeSum();
  }
}
