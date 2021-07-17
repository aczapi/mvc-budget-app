<?php

namespace App\Controllers;

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
  public function index()
  {
    echo 'Home controller!';

    echo '<p>Query string parameters: <pre>' .
      htmlspecialchars(print_r($_GET, true)) . '</pre></p>';
  }
}
