<?php

namespace App\Models;

use \Core\View;
use \App\Auth;
use \App\Date;

use PDO;

class Balances extends \Core\Model
{

  public static function getStartDate()
  {
    if (isset($_POST['time-period'])) {
      $time_period = $_POST['time-period'];
      if ($time_period == 'current-month') {
        $startDate = date('Y/m/01');
      }
      if ($time_period == 'previous-month') {
        $startDate = date('Y/m/d', strtotime('first day of last month'));
      }
      if ($time_period == 'current-year') {
        $startDate = date('Y/01/01');
      }
      if ($time_period == 'custom') {
        $startDate = $_POST['startDate'];
      }
    }
    return $startDate;
  }

  public static function getEndDate()
  {
    if (isset($_POST['time-period'])) {
      $time_period = $_POST['time-period'];
      if ($time_period == 'current-month') {
        $endDate = date('Y/m/t');
      }
      if ($time_period == 'previous-month') {
        $endDate = date('Y/m/d', strtotime('last day of last month'));
      }
      if ($time_period == 'current-year') {
        $endDate = date('Y/m/t');
      }
      if ($time_period == 'custom') {
        $endDate = $_POST['endDate'];
      }
    }

    return $endDate;
  }

  public static function getSumOfAllExpensesByCategory()
  {
    $user = Auth::getUser();

    if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
      $startDate = $_POST['startDate'];
      $endDate = $_POST['endDate'];
    } else {
      $startDate = static::getStartDate();
      $endDate = static::getEndDate();
    }

    $db = static::getDB();

    $sql = 'SELECT expenses_category_assigned_to_users.name AS expense_category_name,
    SUM(expenses.amount) AS sum_expenses
    FROM expenses_category_assigned_to_users, expenses, users
    WHERE users.id = :user_id
    AND users.id = expenses.user_id
    AND expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id
    AND expenses.date_of_expense BETWEEN :startDate AND :endDate
    GROUP BY expenses_category_assigned_to_users.name';

    $sumOfAllExpensesByCategory = $db->prepare($sql);
    $sumOfAllExpensesByCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $sumOfAllExpensesByCategory->bindValue(':startDate', $startDate, PDO::PARAM_STR);
    $sumOfAllExpensesByCategory->bindValue(':endDate', $endDate, PDO::PARAM_STR);
    $sumOfAllExpensesByCategory->execute();

    echo json_encode($sumOfAllExpensesByCategory->fetchAll(PDO::FETCH_ASSOC));
  }

  public static function getSumOfAllIncomesByCategory()
  {
    $user = Auth::getUser();

    if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
      $startDate = $_POST['startDate'];
      $endDate = $_POST['endDate'];
    } else {
      $startDate = static::getStartDate();
      $endDate = static::getEndDate();
    }

    $db = static::getDB();

    $sql = 'SELECT incomes_category_assigned_to_users.name AS income_category_name,
    SUM(incomes.amount) AS sum_incomes
    FROM incomes_category_assigned_to_users, incomes, users
    WHERE users.id = :user_id
    AND users.id = incomes.user_id
    AND incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id
    AND incomes.date_of_income BETWEEN :startDate AND :endDate
    GROUP BY incomes_category_assigned_to_users.name';

    $sumOfAllIncomesByCategory = $db->prepare($sql);
    $sumOfAllIncomesByCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $sumOfAllIncomesByCategory->bindValue(':startDate', $startDate, PDO::PARAM_STR);
    $sumOfAllIncomesByCategory->bindValue(':endDate', $endDate, PDO::PARAM_STR);
    $sumOfAllIncomesByCategory->execute();

    echo json_encode($sumOfAllIncomesByCategory->fetchAll(PDO::FETCH_ASSOC));
  }

  public static function getIndividualExpenses()
  {
    $user = Auth::getUser();

    $startDate = static::getStartDate();
    $endDate = static::getEndDate();

    $db = static::getDB();

    $sql = 'SELECT expenses_category_assigned_to_users.name AS expense_category_name, expenses.date_of_expense,
    expenses.amount, payment_methods_assigned_to_users.name AS payment, expenses.expense_comment, expenses.id
    FROM expenses_category_assigned_to_users, expenses, users, payment_methods_assigned_to_users
    WHERE users.id = :user_id
    AND users.id = expenses.user_id
    AND expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id
    AND expenses.payment_method_assigned_to_user_id = payment_methods_assigned_to_users.id
    AND expenses.date_of_expense BETWEEN :startDate AND :endDate';

    $individualExpenses = $db->prepare($sql);
    $individualExpenses->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $individualExpenses->bindValue(':startDate', $startDate, PDO::PARAM_STR);
    $individualExpenses->bindValue(':endDate', $endDate, PDO::PARAM_STR);
    $individualExpenses->execute();

    return $individualExpenses->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getIndividualIncomes()
  {
    $user = Auth::getUser();

    $startDate = static::getStartDate();
    $endDate = static::getEndDate();

    $db = static::getDB();

    $sql = 'SELECT incomes_category_assigned_to_users.name AS income_category_name, incomes.date_of_income,
    incomes.amount, incomes.income_comment, incomes.id
    FROM incomes_category_assigned_to_users, incomes, users
    WHERE users.id = :user_id
    AND users.id = incomes.user_id
    AND incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id
    AND incomes.date_of_income BETWEEN :startDate AND :endDate';

    $individualIncomes = $db->prepare($sql);
    $individualIncomes->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $individualIncomes->bindValue(':startDate', $startDate, PDO::PARAM_STR);
    $individualIncomes->bindValue(':endDate', $endDate, PDO::PARAM_STR);
    $individualIncomes->execute();

    return $individualIncomes->fetchAll(PDO::FETCH_ASSOC);
  }
}
