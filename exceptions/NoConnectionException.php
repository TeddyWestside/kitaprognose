<?php
/**
 * Eine maßgeschneiderte Exceptionklasse definieren
 */
class NoConnectionException extends Exception
{
  protected $title = "NoConnectionException";
  protected $message = "";

    public function __construct($message) {
        // sicherstellen, dass alles korrekt zugewiesen wird
          $this->message = $message;
    }

    public function getTitle(){
      return $this->title;
    }
}
