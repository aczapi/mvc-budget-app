<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balances;
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
        'paymentMethods' => Expenses::getPaymentMethodsAssignToUser(),     //update??
        'sumExpensesByCategories' => Balances::getSumOfAllExpensesByCategory(),
        'sumIncomesByCategories' => Balances::getSumOfAllIncomesByCategory(),
        'individualExpenses' => Balances::getIndividualExpenses(),
        'individualIncomes' => Balances::getIndividualIncomes()
      ]);
    }
  }
}
