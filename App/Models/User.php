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

  /**
   * Error messages
   * 
   * @var array
   */
  public $errors = [];

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
   */

  public function save()
  {
    $this->validate();

    if (empty($this->errors)) {
      $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

      $sql = 'INSERT INTO users VALUES (NULL, :login, :password_hash, :email)';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':login', $this->login, PDO::PARAM_STR);
      $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
      $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

      return $stmt->execute();
    }
    return false;
  }

  /**
   * Validate current property values, adding validation error messages to the errors array property
   * 
   * @return void
   */

  public function validate()
  {
    //Login
    if ((strlen($this->login) < 3) || (strlen($this->login) > 30)) {
      $this->errors['login'] = "Username must be between 3 and 30 characters";
    }

    //Email adress
    $emailS = filter_var($this->email, FILTER_SANITIZE_EMAIL);
    if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false || $emailS !== $this->email) {
      $this->errors['email'] = "Invalid email";
    }

    if (static::emailExists($this->email)) {
      $this->errors['email'] = 'Email already taken';
    }

    //Password
    // if ($this->password != $this->password_confirmation) {
    //   $this->errors['password'] = "Password must match confirmation";
    // }

    if (strlen($this->password) < 8) {
      $this->errors['password'] = "Please enter at least 8 characters for the password";
    }

    if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
      $this->errors['password'] = "Password needs at least one letter";
    }
    if (preg_match('/.*\d+.*/i', $this->password) == 0) {
      $this->errors['password'] = "Password needs at least one number";
    }
  }

  /**
   * See if a user record already exists with the specified email
   *
   * @param string $email email address to search for
   *
   * @return boolean  True if a record already exists with the specified email, false otherwise
   */
  public static function emailExists($email)
  {
    return static::findByEmail($email) !== false;
  }
  /**
   * Find a user model by email address
   *
   * @param string $email email address to search for
   *
   * @return mixed User object if found, false otherwise
   */
  public static function findByEmail($email)
  {
    $sql = 'SELECT * FROM users WHERE email = :email';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);

    $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

    $stmt->execute();

    return $stmt->fetch();
  }
}
