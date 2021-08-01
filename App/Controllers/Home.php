<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 */
class Home extends \Core\Controller
{
  /**
   * Show the index page
   *
   * @return void
   */
  public function indexAction()
  {
    \App\Mail::send('nessy0991@gmail.com', 'Test', 'This is a test', '<h2>This is a test</h2>');

    View::renderTemplate('Home/index.html');
  }
}
