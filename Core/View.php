<?php

namespace Core;

/**
 * View
 *
 */

class View
{

  /**
   * Render a view file
   *
   * @param string $view  The view file
   *
   * @return void
   */
  public static function render($view, $args = [])
  {
    extract($args, EXTR_SKIP);
    $file = "../App/Views/$view";

    if (is_readable($file)) {
      require $file;
    } else {
      echo "$file not found";
    }
  }

  /**
   * Render a view template using Twig
   *
   * @param string $template  The template file
   * @param array $args  Associative array of data to display in the view (optional)
   *
   * @return void
   */
  public static function renderTemplate($template, $args = [])
  {
    echo static::getTemplate($template, $args);
  }

  /**
   * Render a view template using Twig
   *
   * @param string $template  The template file
   * @param array $args  Associative array of data to display in the view (optional)
   *
   * @return void
   */
  public static function getTemplate($template, $args = [])
  {
    static $twig = null;

    if ($twig === null) {
      $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
      $twig = new \Twig\Environment($loader);
      $twig->addGlobal('current_user', \App\Auth::getUser());
      $twig->addGlobal('flash_messages', \App\Flash::getMessages());
    }

    return $twig->render($template, $args);
  }
}
