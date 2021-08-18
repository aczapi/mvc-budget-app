<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balances;


class Balance extends Authenticated
{
  public function showAction()
  {
    if (isset($_POST['time-period'])) {
      View::renderTemplate('Balance/show-balance.html', [
        'startDate' => Balances::getStartDate(),
        'endDate' => Balances::getEndDate(),
        'sumExpenseByCategories' => Balances::getSumOfAllExpensesByCategory()
      ]);
    }

    // var_dump(Balances::getSumOfAllExpensesByCategory());
  }
}
