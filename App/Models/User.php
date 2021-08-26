<?php

namespace App\Models;

use App\Token;
use App\Mail;
use \Core\View;
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

      $token = new Token();
      $hashed_token = $token->getHash();
      $this->activation_token = $token->getValue();

      $sql = 'INSERT INTO users(login, password, email, activation_hash) VALUES (:login, :password_hash, :email, :activation_hash)';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':login', $this->login, PDO::PARAM_STR);
      $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
      $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
      $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);

      $user = $stmt->execute();

      $this->addUserDefaultExpenses($this->email);
      $this->addUserDefaultIncomes($this->email);
      $this->addUserDefaultPaymentMethods($this->email);

      return $user;
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

    if (static::emailExists($this->email, $this->id ?? null)) {
      $this->errors['email'] = 'Email already taken';
    }

    //Password
    // if ($this->password != $this->password_confirmation) {
    //   $this->errors['password'] = "Password must match confirmation";
    // }

    // Password
    if (isset($this->password)) {

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
  }

  /**
   * See if a user record already exists with the specified email
   *
   * @param string $email email address to search for
   *
   * @return boolean  True if a record already exists with the specified email, false otherwise
   */
  public static function emailExists($email, $ignore_id = null)
  {
    $user = static::findByEmail($email);

    if ($user) {
      if ($user->id != $ignore_id) {
        return true;
      }
    }
    return false;
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

  /**
   * Authenticate a user by email and password.
   *
   * @param string $email email address
   * @param string $password password
   *
   * @return mixed  The user object or false if authentication fails
   */
  public static function authenticate($email, $password)
  {
    $user = static::findByEmail($email);

    if ($user && $user->is_active) {
      if (password_verify($password, $user->password)) {
        return $user;
      }
    }

    return false;
  }
  /**
   * Find a user model by id
   *
   * @param string $id  The user ID
   *
   * @return mixed User object if found, false otherwise
   */
  public static function findByID($id)
  {
    $sql = 'SELECT * FROM users WHERE id = :id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

    $stmt->execute();

    return $stmt->fetch();
  }

  /**
   * Reember the login by inserting a new unique token into remembered_logins table
   * for this user record
   * 
   * @return boolean True if the login was remembered successfully, false otherwise
   */
  public function rememberLogin()
  {

    $token = new Token();
    $hashed_token = $token->getHash();
    $this->remember_token = $token->getValue();

    $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;

    $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
    VALUES (:token_hash, :user_id, :expires_at)';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
    $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

    return $stmt->execute();
  }
  /**
   * Send password reset instructions to the user specified
   * 
   * @param string $email The email address
   * 
   * @return void
   */
  public static function sendPasswordReset($email)
  {
    $user = static::findByEmail($email);

    if ($user) {

      if ($user->startPasswordReset()) {

        $user->sendPasswordResetEmail();
      }
    }
  }

  /**
   * Start the password reset process by generating a new token and expiry
   * 
   * @param string $email The email address
   * 
   * @return void
   */
  protected function startPasswordReset()
  {
    $token = new Token();
    $hashed_token = $token->getHash();
    $this->password_reset_token = $token->getValue();

    $expiry_timestamp = time() + 60 * 60 * 4; //2 hours from now

    $sql = 'UPDATE users 
            SET password_reset_hash = :token_hash,
                password_reset_expires_at = :expires_at
            WHERE id = :id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
    $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
    $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  /**
   * Send password reset instructions in an email to the user
   * 
   * @return void
   */

  protected function sendPasswordResetEmail()
  {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;

    // $text = "Please click on the following URL to reset your password: $url";
    // $html = "Please click <a href = \"$url\">here</a> to reset your password";
    $text = View::getTemplate('Password/reset_email.txt', ['url' => $url]);
    $html = View::getTemplate('Password/reset_email.html', ['url' => $url]);

    Mail::send($this->email, 'Password reset', $text, $html);
  }

  /**
   * Find a user model by password reset token and expiry
   *
   * @param string $token Password reset token sent to user
   *
   * @return mixed User object if found and the token hasn't expired, null otherwise
   */

  public static function findByPasswordReset($token)
  {
    $token = new Token($token);
    $hashed_token = $token->getHash();

    $sql = 'SELECT * FROM users WHERE password_reset_hash = :token_hash';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);

    $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

    $stmt->execute();

    $user = $stmt->fetch();

    if ($user) {
      //Check password reset token hasn't expired
      if (strtotime($user->password_reset_expires_at) > time()) {
        return $user;
      }
    }
  }

  /**
   * Reset the password
   * 
   * @param string $password The new password
   * 
   * @return boolean True if the password was updated successfully, false otherwise
   */
  public function resetPassword($password)
  {
    $this->password = $password;

    $this->validate();

    if (empty($this->errors)) {

      $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

      $sql = 'UPDATE users
              SET password = :password_hash,
                  password_reset_hash = NULL,
                  password_reset_expires_at = NULL
              WHERE id = :id';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
      $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

      return $stmt->execute();
    }

    return false;
  }

  /**
   * Send an email to the user containing the activation link
   * 
   * @return void
   */

  public function sendActivationEmail()
  {
    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token;

    $text = View::getTemplate('SignUp/activation_email.txt', ['url' => $url]);
    $html = View::getTemplate('SignUp/activation_email.html', ['url' => $url]);

    Mail::send($this->email, 'Account activation', $text, $html);
  }

  /**
   * Activate the user account with the specified activation token
   * 
   * @param string $value Activation token from the URL
   * 
   * @return void
   */
  public static function activate($value)
  {
    $token = new Token($value);
    $hashed_token = $token->getHash();

    $sql = 'UPDATE users
                SET is_active = 1,
                    activation_hash = null
                WHERE activation_hash = :hashed_token';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

    $stmt->execute();
  }

  /**
   * Update the user's profile
   * 
   * @param array $data Data from the edit profile form
   * 
   * @return boolean True if the data was updated, false otherwise
   */

  public function updateProfileName($data)
  {
    $this->login = $data['login'];

    $this->validate();

    if (empty($this->errors)) {

      $sql = 'UPDATE users
              SET login = :login
              WHERE id = :id';


      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':login', $this->login, PDO::PARAM_STR);
      $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

      return $stmt->execute();
    }
    return false;
  }

  public function updateProfileEmail($data)
  {
    $this->email = $data['email'];

    $this->validate();

    if (empty($this->errors)) {

      $sql = 'UPDATE users
              SET email = :email
              WHERE id = :id';


      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
      $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

      return $stmt->execute();
    }
    return false;
  }

  public static function validateOldPassword($password, $userId)
  {
    $user = static::findByID($userId);

    if ($user) {
      if (password_verify($password, $user->password)) {
        return true;
      }
    }

    return false;
  }

  public function updatePassword($data)
  {
    $this->old_password = $data['old_password'];
    $this->new_password = $data['password'];

    $this->validate();

    $is_valid = static::validateOldPassword($this->old_password, $_SESSION['user_id']);

    if (empty($this->errors) && $is_valid) {

      $sql = 'UPDATE users
              SET password = :password_hash
              WHERE id = :id';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $password_hash = password_hash($this->new_password, PASSWORD_DEFAULT);
      $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
      $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

      return $stmt->execute();
    }
    return false;
  }

  protected function addUserDefaultExpenses($email)
  {
    $sql = 'INSERT INTO expenses_category_assigned_to_users(user_id, name) SELECT users.id, expenses_category_default.name FROM users, expenses_category_default WHERE users.email = :email';

    $db = static::getDB();
    $defaultExpensesCategory = $db->prepare($sql);

    $defaultExpensesCategory->bindParam(':email', $email, PDO::PARAM_STR);
    $defaultExpensesCategory->execute();
  }

  protected function addUserDefaultIncomes($email)
  {
    $sql = 'INSERT INTO incomes_category_assigned_to_users(user_id, name) SELECT users.id, incomes_category_default.name FROM users, incomes_category_default WHERE users.email = :email';

    $db = static::getDB();
    $defaultIncomesCategory = $db->prepare($sql);

    $defaultIncomesCategory->bindParam(':email', $email, PDO::PARAM_STR);
    $defaultIncomesCategory->execute();
  }

  protected function addUserDefaultPaymentMethods($email)
  {
    $sql = 'INSERT INTO payment_methods_assigned_to_users(user_id, name) SELECT users.id, payment_methods_default.name FROM users, payment_methods_default WHERE users.email = :email';

    $db = static::getDB();
    $defaultPaymentMethods = $db->prepare($sql);

    $defaultPaymentMethods->bindParam(':email', $email, PDO::PARAM_STR);
    $defaultPaymentMethods->execute();
  }
}
