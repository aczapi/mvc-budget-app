<?php

namespace App\Models;

use \Core\View;
use \App\Auth;
use \App\Date;

use PDO;

class Balances extends \Core\Model
{

  public function getSumOfAllExpensesByCategory()
  {
    $user = Auth::getUser();

    $sql = 'SELECT expenses_category_assigned_to_users.name,
    SUM(expenses.amount) AS sum_expenses
    FROM expenses_category_assigned_to_users, expenses, users
    WHERE MONTH(expenses.date_of_expense) = static::getCurrent
    AND YEAR(expenses.date_of_expense) = $currentYear AND users.id = $user_id
    AND users.id = expenses.user_id
    AND expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id
    GROUP BY expenses_category_assigned_to_users.name';

    $db = static::getDB();
    $userExpensesCategory = $db->prepare($sql);


    $usersQuery1 = $db->prepare("SELECT expenses_category_assigned_to_users.name,
    SUM(expenses.amount) AS sum_expenses
    FROM expenses_category_assigned_to_users, expenses, users
    WHERE MONTH(expenses.date_of_expense) = $currentMonth
    AND YEAR(expenses.date_of_expense) = $currentYear AND users.id = $user_id
    AND users.id = expenses.user_id
    AND expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id
    GROUP BY expenses_category_assigned_to_users.name");
    $usersQuery1->execute();


    $expenses = $usersQuery1->fetchAll();
  }
}
