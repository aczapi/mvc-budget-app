<?php

namespace App\Controllers;

use \Core\View;


class Balance extends Authenticated
{
  public function showAction()
  {
    if (isset($_POST['time-period'])) {
      View::renderTemplate('Balance/show-balance.html', [
        'selectedTime' => $_POST['time-period']
      ]);
    } else {
      View::renderTemplate('MainMenu/index.html');
    }
  }
}
