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
}
