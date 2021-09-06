<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Expenses;
use \App\Models\Incomes;

/**
 * Profile controller 
 * 
 */

class Settings extends Authenticated
{

  /**
   * Before filter - called before each action method
   * 
   * @return void
   */

  protected function before()
  {
    parent::before();
    $this->user = Auth::getUser();
  }



  /**
   * Show the settings
   * 
   * @return void
   */

  public function showExpenseCategoriesAction()
  {
    View::renderTemplate('Settings/show_expenses_categories.html', [
      'expensesCategories' => Expenses::getExpensesCategoryAssignToUser()
    ]);
  }
  public function showIncomeCategoriesAction()
  {
    View::renderTemplate('Settings/show_incomes_categories.html', [
      'incomesCategories' => Incomes::getIncomesCategoryAssignToUser()
    ]);
  }
  public function showPaymentCategoriesAction()
  {
    View::renderTemplate('Settings/show_payment_categories.html', [
      'paymentCategories' => Expenses::getPaymentMethodsAssignToUser()
    ]);
  }
}
