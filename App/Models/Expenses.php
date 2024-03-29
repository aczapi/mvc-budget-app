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


    $sql = 'SELECT name, category_limit,id FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name != :name';

    $db = static::getDB();
    $userExpensesCategory = $db->prepare($sql);

    $userExpensesCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $userExpensesCategory->bindValue(':name', 'Another', PDO::PARAM_STR);
    $userExpensesCategory->execute();

    return $userExpensesCategory->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function getPaymentMethodsAssignToUser()
  {
    $user = Auth::getUser();

    $sql = 'SELECT name,id FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name != :name';

    $db = static::getDB();
    $userPaymentMethods = $db->prepare($sql);

    $userPaymentMethods->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $userPaymentMethods->bindValue(':name', 'Another', PDO::PARAM_STR);
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

    if ($this->amount == 0) {
      $this->delete();
      echo 1;
    }

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

  public function delete()
  {
    $user = Auth::getUser();

    $sql = "DELETE FROM expenses WHERE id = :id";

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public function validateNewCategoryName()
  {
    $user = Auth::getUser();

    $sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :name";

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

      $sql = "INSERT INTO expenses_category_assigned_to_users(id,user_id,name) VALUES (NULL, :user_id, :name)";

      $db = static::getDB();
      $newCategory = $db->prepare($sql);
      $newCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
      $newCategory->bindValue(':name', $this->category, PDO::PARAM_STR);
      $newCategory->execute();

      echo $this->getIdOfExpense($user->id);
    }
    return false;
  }

  public function getIdOfAnotherCategory($user_id)
  {
    $sql = 'SELECT id FROM expenses_category_assigned_to_users WHERE name = :name AND user_id = :user_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':name', 'Another', PDO::PARAM_STR);

    $stmt->execute();

    $expenseAnother = $stmt->fetch(PDO::FETCH_ASSOC);

    return $expenseAnother['id'];
  }

  public function moveToAnotherCategory($user_id)
  {
    $sql = "UPDATE expenses
				SET expense_category_assigned_to_user_id = :another_category_id 
				WHERE expense_category_assigned_to_user_id = :category_id";

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':category_id', $this->getIdOfExpense($user_id), PDO::PARAM_INT);
    $stmt->bindValue(':another_category_id', $this->getIdOfAnotherCategory($user_id), PDO::PARAM_INT);

    return  $stmt->execute();
  }


  public function deleteCategory()
  {
    $user = Auth::getUser();

    $this->moveToAnotherCategory($user->id);

    $sql = "DELETE FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :name";

    $db = static::getDB();
    $deletedCategory = $db->prepare($sql);
    $deletedCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $deletedCategory->bindValue(':name', $this->category, PDO::PARAM_STR);

    echo $deletedCategory->execute();
  }


  public function updateCategory()
  {
    $user = Auth::getUser();

    $sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :name AND id <> :id";

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $this->category, PDO::PARAM_STR);
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) == 1) {
      $this->errors['category'] = "Category already exists";
    }

    if (empty($this->errors)) {
      $sql = "UPDATE expenses_category_assigned_to_users SET name = :name, category_limit = :category_limit WHERE user_id = :user_id AND id = :id";

      $db = static::getDB();
      $updatedCategory = $db->prepare($sql);
      $updatedCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
      $updatedCategory->bindValue(':name', $this->category, PDO::PARAM_STR);
      if ($this->categoryLimit == "") {
        $updatedCategory->bindValue(':category_limit', NULL, PDO::PARAM_STR);
      } else {
        $updatedCategory->bindValue(':category_limit', $this->categoryLimit, PDO::PARAM_STR);
      }
      $updatedCategory->bindValue(':id', $this->id, PDO::PARAM_INT);

      echo $updatedCategory->execute();
    }
    return false;
  }

  public function validateNewPaymentName()
  {
    $user = Auth::getUser();

    $sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name = :name";

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $this->payment, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) == 1) {
      $this->errors['payment'] = "Payment method already exists";
    }
  }

  public function addNewPayment()
  {
    $user = Auth::getUser();

    $this->validateNewPaymentName();

    if (empty($this->errors)) {

      $sql = "INSERT INTO payment_methods_assigned_to_users VALUES (NULL, :user_id, :name)";

      $db = static::getDB();
      $newPayment = $db->prepare($sql);
      $newPayment->bindValue(':user_id', $user->id, PDO::PARAM_INT);
      $newPayment->bindValue(':name', $this->payment, PDO::PARAM_STR);
      $newPayment->execute();

      echo $this->getIdOfPaymentMethod($user->id);
    }
    return false;
  }

  public function getIdOfAnotherPayment($user_id)
  {
    $sql = 'SELECT id FROM payment_methods_assigned_to_users WHERE name = :name AND user_id = :user_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':name', 'Another', PDO::PARAM_STR);

    $stmt->execute();

    $paymentAnother = $stmt->fetch(PDO::FETCH_ASSOC);

    return $paymentAnother['id'];
  }

  public function moveToAnotherPayment($user_id)
  {
    $sql = "UPDATE expenses
				SET payment_method_assigned_to_user_id = :another_payment_id 
				WHERE payment_method_assigned_to_user_id = :payment_id";

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':payment_id', $this->getIdOfPaymentMethod($user_id), PDO::PARAM_INT);
    $stmt->bindValue(':another_payment_id', $this->getIdOfAnotherPayment($user_id), PDO::PARAM_INT);

    return  $stmt->execute();
  }

  public function deletePayment()
  {
    $user = Auth::getUser();

    $this->moveToAnotherPayment($user->id);

    $sql = "DELETE FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name = :name";

    $db = static::getDB();
    $deletedPayment = $db->prepare($sql);
    $deletedPayment->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $deletedPayment->bindValue(':name', $this->payment, PDO::PARAM_STR);

    echo $deletedPayment->execute();
  }


  public function updatePayment()
  {
    $user = Auth::getUser();

    $sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name = :name AND id <> :id";

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $this->payment, PDO::PARAM_STR);
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) == 1) {
      $this->errors['payment'] = "Payment method already exists";
    }

    if (empty($this->errors)) {
      $sql = "UPDATE payment_methods_assigned_to_users SET name = :name WHERE user_id = :user_id AND id = :id";

      $db = static::getDB();
      $updatedPayment = $db->prepare($sql);
      $updatedPayment->bindValue(':user_id', $user->id, PDO::PARAM_INT);
      $updatedPayment->bindValue(':name', $this->payment, PDO::PARAM_STR);
      $updatedPayment->bindValue(':id', $this->id, PDO::PARAM_INT);

      echo $updatedPayment->execute();
    }
    return false;
  }

  public function getLimit()
  {
    $categoryLimit = $this->getLimitForCategory();
    $sumOfExpensesInCategory = $this->getSumOfExpensesInCategory();
    $difference = $categoryLimit - $sumOfExpensesInCategory;

    if ($this->amount != "") {
      $totalSum = $sumOfExpensesInCategory + $this->amount;
    }
    if ($categoryLimit != null) {
      echo "Monthly limit: " . $categoryLimit . "<br>";
      if ($sumOfExpensesInCategory != "") {
        echo "Expenses in this month: " . $sumOfExpensesInCategory . "<br>";
      } else {
        echo "Expenses in this month: 0<br>";
      }
      echo "Difference: " . number_format($difference, 2, '.', '') . "<br>";
      if ($this->amount != "") {
        echo "Expenses + entered amount: " . number_format($totalSum, 2, '.', '');
      }
    }
  }

  public function getLimitForCategory()
  {
    $user = Auth::getUser();

    $sql = "SELECT category_limit FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name= :name";

    $db = static::getDB();

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $stmt->bindValue(':name', $this->category, PDO::PARAM_STR);
    $stmt->execute();

    $limit = $stmt->fetch(PDO::FETCH_ASSOC);

    return $limit['category_limit'];
  }

  public function getSumOfExpensesInCategory()
  {
    $month = date("m", strtotime($_POST['date']));
    $year = date("Y", strtotime($_POST['date']));

    $user = Auth::getUser();
    $sql = "SELECT SUM(expenses.amount) AS sum_expenses FROM expenses WHERE MONTH(expenses.date_of_expense) = $month
    AND YEAR(expenses.date_of_expense) = $year AND expense_category_assigned_to_user_id = :id";

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':id', $this->getIdOfExpense($user->id), PDO::PARAM_INT);
    $stmt->execute();

    $sum = $stmt->fetch(PDO::FETCH_ASSOC);

    return $sum['sum_expenses'];
  }

  public function getValue()
  {
    if ($this->amount != "") {

      $categoryLimit = $this->getLimitForCategory();
      $sumOfExpensesInCategory = $this->getSumOfExpensesInCategory();
      $totalSum = $sumOfExpensesInCategory + $this->amount;
      if ($totalSum <= $categoryLimit) {
        echo false;
      } else {
        echo true;
      }
    }
  }
}
