<?php

namespace App\Models;

use PDO;

/**
 * User model
 *
 * PHP version
 */
class User extends \Core\Model
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

  /**
   * Save the user model with current property values
   * 
   * @return void
   */

  public function save()
  {

    $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

    $sql = 'INSERT INTO users VALUES (NULL, :login, :password_hash, :email)';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':login', $this->login, PDO::PARAM_STR);
    $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
    $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

    return $stmt->execute();
  }
}
