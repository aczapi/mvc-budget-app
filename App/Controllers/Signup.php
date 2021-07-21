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

      $this->redirect('/signup/success');
    } else {
      View::renderTemplate('SignUp/new-user.html', [
        'user' => $user
      ]);
    }
  }

  /**
   * Show the signup success page
   *
   * @return void
   */
  public function successAction()
  {
    View::renderTemplate('SignUp/success.html');
  }
}
