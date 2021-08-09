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

    $sql = 'SELECT login FROM expenses_category_assigned_to_users WHERE user_id = :user_id';

    $db = static::getDB();
    $userExpensesCategory = $db->prepare($sql);

    $userExpensesCategory->bindValue(':user_id', $user->id, PDO::PARAM_INT);
    $userExpensesCategory->execute();

    return $userExpensesCategory->fetchAll(PDO::FETCH_ASSOC);
  }
}
