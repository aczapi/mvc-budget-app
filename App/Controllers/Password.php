<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;

/**
 * Password controller
 * 
 * 
 */

class Password extends \Core\Controller
{
  /**
   * Show th forgotten password page
   * 
   * @return void
   */
  public function forgotAction()
  {
    View::renderTemplate('Password/forgot.html');
  }
  /**
   * Send the password reset link to the supplied email
   * 
   * @return void
   */
  public function requestResetAction()
  {
    Flash::addMessage('Please check your email');
    User::sendPasswordReset($_POST['email']);

    View::renderTemplate('Password/reset_requested.html');
  }

  /**
   * Show the reset password form
   * 
   * @return void
   * 
   */
  public function resetAction()
  {
    $token = $this->route_params['token'];

    $user = User::findByPasswordReset($token);

    var_dump($user);
  }
}
