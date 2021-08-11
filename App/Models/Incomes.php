<?php

namespace App\Models;

use \Core\View;
use \App\Auth;

use PDO;


class Incomes extends \Core\Model
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

  public static function getIncomesCategoryAssignToUser()
  {
    $user = Auth::getUser();


    $sql = 'SELECT name FROM incomes_category_assigned_to_users WHERE user_id = :user_id';

    $db = static::getDB();
    $userIncomesCategory = $db->prepare($sql);

    $userIncomesCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $userIncomesCategory->execute();

    return $userIncomesCategory->fetchAll(PDO::FETCH_ASSOC);
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

    if (strlen($this->comment) > 100) {
      $this->errors['comment'] = "Comment can't be longer than 100 characters";
    }
  }

  protected function getIdOfIncome($user_id)
  {
    $sql = 'SELECT id FROM incomes_category_assigned_to_users WHERE name = :name AND user_id = :user_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    // echo $this->category;
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $this->category, PDO::PARAM_STR);

    $stmt->execute();

    $income = $stmt->fetch(PDO::FETCH_ASSOC);

    // echo $income['id'];
    return $income['id'];
  }

  public function save()
  {
    $user = Auth::getUser();

    $this->validate();

    if (empty($this->errors)) {

      $sql = 'INSERT INTO incomes VALUES (NULL, :user_id, :category_id, :amount, :date, :comment)';

      $db = static::getDB();
      $addedIncome = $db->prepare($sql);

      $addedIncome->bindValue(':user_id', $user->id, PDO::PARAM_INT);
      $addedIncome->bindValue(':category_id', $this->getIdOfIncome($user->id), PDO::PARAM_INT);
      $addedIncome->bindValue(':amount', $this->amount, PDO::PARAM_STR);
      $addedIncome->bindValue(':date', $this->date, PDO::PARAM_STR);
      $addedIncome->bindValue(':comment', $this->comment, PDO::PARAM_STR);

      return $addedIncome->execute();
    }

    return false;
  }
}
