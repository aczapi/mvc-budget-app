<?php

namespace App\Models;

use \Core\View;
use \App\Auth;

use PDO;


class Expenses extends \Core\Model
{
  /**Class constructor 
   * 
   * @param array $data Initial property values
   * 
   * @return void
   */
  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

  public static function getExpensesCategoryAssignToUser()
  {
    $user = Auth::getUser();


    $sql = 'SELECT name FROM expenses_category_assigned_to_users WHERE user_id = :user_id';

    $db = static::getDB();
    $userExpensesCategory = $db->prepare($sql);

    $userExpensesCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $userExpensesCategory->execute();

    return $userExpensesCategory->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getPaymentMethodsAssignToUser()
  {
    $user = Auth::getUser();

    $sql = 'SELECT name FROM payment_methods_assigned_to_users WHERE user_id = :user_id';

    $db = static::getDB();
    $userPaymentMethods = $db->prepare($sql);

    $userPaymentMethods->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $userPaymentMethods->execute();

    return $userPaymentMethods->fetchAll(PDO::FETCH_ASSOC);
  }

  public function validate()
  {
    if ($this->amount < 0.01 && $this->amount != "0") {
      $this->errors['amount'] = "Amount can't be less than 0.01";
    }

    if ($this->date < '2000-01-01') {
      $this->errors['date'] = "Date cannot be before 2000-01-01";
    }
    if (!isset($this->date) || $this->date == NULL) {
      $this->errors['date'] = "Select date";
    }
    if (!isset($this->category)) {
      $this->errors['category'] = "Select category";
    }

    if (!isset($this->payment)) {
      $this->errors['payment'] = "Select payment method";
    }

    if (strlen($this->comment) > 100) {
      $this->errors['comment'] = "Comment can't be longer than 100 characters";
    }
  }

  protected function getIdOfExpense($user_id)
  {
    $sql = 'SELECT id FROM expenses_category_assigned_to_users WHERE name = :name AND user_id = :user_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $this->category, PDO::PARAM_STR);

    $stmt->execute();

    $expense = $stmt->fetch(PDO::FETCH_ASSOC);
    return $expense['id'];
  }


  protected function getIdOfPaymentMethod($user_id)
  {
    $sql = 'SELECT id FROM payment_methods_assigned_to_users WHERE name = :name AND user_id = :user_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $this->payment, PDO::PARAM_STR);

    $stmt->execute();

    $payment_method = $stmt->fetch(PDO::FETCH_ASSOC);
    return $payment_method['id'];
  }

  public function save()
  {
    $user = Auth::getUser();

    $this->validate();

    if (empty($this->errors)) {

      $sql = 'INSERT INTO expenses VALUES (NULL, :user_id, :category_id,:payment_id, :amount, :date, :comment)';

      $db = static::getDB();
      $addedExpense = $db->prepare($sql);

      $addedExpense->bindValue(':user_id', $user->id, PDO::PARAM_INT);
      $addedExpense->bindValue(':category_id', $this->getIdOfExpense($user->id), PDO::PARAM_INT);
      $addedExpense->bindValue(':payment_id', $this->getIdOfPaymentMethod($user->id), PDO::PARAM_INT);
      $addedExpense->bindValue(':amount', $this->amount, PDO::PARAM_STR);
      $addedExpense->bindValue(':date', $this->date, PDO::PARAM_STR);
      $addedExpense->bindValue(':comment', $this->comment, PDO::PARAM_STR);

      return $addedExpense->execute();
    }

    return false;
  }

  public function update()
  {
    $user = Auth::getUser();

    $this->validate();

    if (empty($this->errors)) {

      $sql = "UPDATE expenses SET expense_category_assigned_to_user_id = :category_id, payment_method_assigned_to_user_id = :payment_id, amount = :amount, date_of_expense = :date, expense_comment = :comment WHERE id = :id";

      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
      $stmt->bindValue(':category_id', $this->getIdOfExpense($user->id), PDO::PARAM_INT);
      $stmt->bindValue(':payment_id', $this->getIdOfPaymentMethod($user->id), PDO::PARAM_INT);
      $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
      $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
      $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

      return $stmt->execute();
    }
    return false;
  }
}
