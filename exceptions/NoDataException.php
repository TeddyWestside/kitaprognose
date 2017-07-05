<?php
/**
 * Eine maßgeschneiderte Exceptionklasse definieren
 */
class NoDataException extends Exception
{
  protected $title = "NoDataException";
  protected $message = "";

    public function __construct($message) {
        // sicherstellen, dass alles korrekt zugewiesen wird
          $this->message = $message;
    }

    public function getTitle(){
      return $this->title;
    }
}
