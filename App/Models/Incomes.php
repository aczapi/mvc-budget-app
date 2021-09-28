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


    $sql = 'SELECT name, id FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name != :name';

    $db = static::getDB();
    $userIncomesCategory = $db->prepare($sql);

    $userIncomesCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $userIncomesCategory->bindValue(':name', 'Another', PDO::PARAM_STR);
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

    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $this->category, PDO::PARAM_STR);

    $stmt->execute();

    $income = $stmt->fetch(PDO::FETCH_ASSOC);

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

  public function update()
  {
    $user = Auth::getUser();

    $this->validate();

    if (empty($this->errors)) {

      $sql = "UPDATE incomes SET income_category_assigned_to_user_id = :category_id, amount = :amount, date_of_income = :date, income_comment = :comment WHERE id = :id";

      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
      $stmt->bindValue(':category_id', $this->getIdOfIncome($user->id), PDO::PARAM_INT);
      $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
      $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
      $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

      return $stmt->execute();
    }
    return false;
  }

  public function delete()
  {
    $user = Auth::getUser();

    $sql = "DELETE FROM incomes WHERE id = :id";

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function validateNewCategoryName()
  {
    $user = Auth::getUser();

    $sql = "SELECT * FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name = :name";

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $this->category, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) == 1) {
      $this->errors['category'] = "Category already exists";
    }
  }

  public function addNewCategory()
  {
    $user = Auth::getUser();

    $this->validateNewCategoryName();

    if (empty($this->errors)) {

      $sql = "INSERT INTO incomes_category_assigned_to_users VALUES (NULL, :user_id, :name)";

      $db = static::getDB();
      $newCategory = $db->prepare($sql);
      $newCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
      $newCategory->bindValue(':name', $this->category, PDO::PARAM_STR);
      $newCategory->execute();

      echo $this->getIdOfIncome($user->id);
    }
    return false;
  }

  public function deleteCategory()
  {
    $user = Auth::getUser();

    $sql = "DELETE FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name = :name";

    $db = static::getDB();
    $deletedCategory = $db->prepare($sql);
    $deletedCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $deletedCategory->bindValue(':name', $this->category, PDO::PARAM_STR);

    echo $deletedCategory->execute();
  }


  public function updateCategory()
  {
    $user = Auth::getUser();

    $this->validateNewCategoryName();

    if (empty($this->errors)) {

      $sql = "UPDATE incomes_category_assigned_to_users SET name = :name WHERE user_id = :user_id AND id = :id";

      $db = static::getDB();
      $updatedCategory = $db->prepare($sql);
      $updatedCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
      $updatedCategory->bindValue(':name', $this->category, PDO::PARAM_STR);
      $updatedCategory->bindValue(':id', $this->id, PDO::PARAM_INT);

      echo $updatedCategory->execute();
    }
    return false;
  }
}
