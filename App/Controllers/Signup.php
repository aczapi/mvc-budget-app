<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Register controller
 *
 */
class Signup extends \Core\Controller
{
  /**
   * Show the register page
   *
   * @return void
   */
  public function indexAction()
  {
    View::renderTemplate('SignUp/new-user.html');
  }

  /**
   * Sign up a new user
   * 
   * @return void
   */
  public function createAction()
  {
    $user = new User($_POST);

    if ($user->save()) {

      View::renderTemplate('SignUp/success.html');
    } else {
      var_dump($user->errors);
    }
  }
}
