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

  public function addNewIncomeCategoryAction()
  {
    if (isset($_POST['category'])) {

      $income = new Incomes($_POST);

      $income->addNewCategory();
    }
  }

  public function deleteIncomeCategoryAction()
  {
    if (isset($_POST['category'])) {

      $income = new Incomes($_POST);

      $income->deleteCategory();
    }
  }

  public function updateIncomeCategoryAction()
  {
    if (isset($_POST['category'])) {

      $income = new Incomes($_POST);

      $income->updateCategory();
    }
  }

  public function addNewExpenseCategoryAction()
  {
    if (isset($_POST['category'])) {

      $expense = new Expenses($_POST);

      $expense->addNewCategory();
    }
  }

  public function deleteExpenseCategoryAction()
  {
    if (isset($_POST['category'])) {

      $expense = new Expenses($_POST);

      $expense->deleteCategory();
    }
  }

  public function updateExpenseCategoryAction()
  {
    if (isset($_POST['category'])) {

      $expense = new Expenses($_POST);

      echo $expense->updateCategory();
    }
  }

  public function addNewPaymentMethodAction()
  {
    if (isset($_POST['payment'])) {

      $expense = new Expenses($_POST);

      $expense->addNewPayment();
    }
  }

  public function deletePaymentMethodAction()
  {
    if (isset($_POST['payment'])) {

      $expense = new Expenses($_POST);

      $expense->deletePayment();
    }
  }

  public function updatePaymentMethodAction()
  {
    if (isset($_POST['payment'])) {

      $expense = new Expenses($_POST);

      echo $expense->updatePayment();
    }
  }
}
