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
      $startDate = date('Y/m/01');
    }
    return $startDate;
  }

  public static function getEndDate()
  {
    if (isset($_POST['time-period'])) {
      $endDate = date('Y/m/d');
    }

    return $endDate;
  }

  public static function getSumOfAllExpensesByCategory()
  {
    $user = Auth::getUser();

    $startDate = static::getStartDate();
    $endDate = static::getEndDate();

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

    return $sumOfAllExpensesByCategory->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getSumOfAllIncomesByCategory()
  {
    $user = Auth::getUser();

    $startDate = static::getStartDate();
    $endDate = static::getEndDate();

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

    return $sumOfAllIncomesByCategory->fetchAll(PDO::FETCH_ASSOC);
  }
}
