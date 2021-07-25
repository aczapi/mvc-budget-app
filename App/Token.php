<?php

namespace App;

/**
 * Using random tokens
 */

class Token
{
  /**
   * Token value
   * @var array
   */
  protected $token;

  /**
   * class constructor, Create a new random token
   * 
   * @return void
   */

  public function __construct($token_value = null)
  {
    if ($token_value) {
      $this->token = $token_value;
    } else {
      $this->token = bin2hex(random_bytes(16));
    }
  }


  /**
   * 
   * Get the token value
   * 
   * @return string The value
   */

  public function getValue()
  {
    return $this->token;
  }

  /**
   * Get the hashed token value
   * 
   * @return string The hashed value
   */

  public function getHash()
  {
    return hash_hmac('sha256', $this->token, \App\Config::SECRET_KEY);
  }
}
