<?php
/**
 * Eine maßgeschneiderte Exceptionklasse definieren
 */
class NoDatabaseException extends Exception
{
  protected $title = "NoDatabaseException";
  protected $message = "";

    public function __construct($message) {
        // sicherstellen, dass alles korrekt zugewiesen wird
          $this->message = $message;
    }

    public function getTitle(){
      return $this->title;
    }
}
