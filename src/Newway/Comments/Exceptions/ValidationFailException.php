<?php namespace Newway\Comments\Exceptions;

class ValidationFailException extends NewwayCommentsException {

  /**
   * @var array
   */
  protected $errors;

  /**
   * @param string $message
   * @param array $errors
   */
  function __construct($message = "", array $errors = array())
  {
    $this->errors = $errors;
    parent::__construct($message);
  }

  /**
   * Get errors
   *
   * @return array
   */
  public function getErrors()
  {
    return $this->errors;
  }

}