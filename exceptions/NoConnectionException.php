<?php

/*  Die NoConnectionException ist eine benutzerdefinierte Exceptinon,
    die geworfen wird, wenn eine Verbindung nicht existiert
    @author Carsten Schober
*/
class NoConnectionException extends Exception
{
  protected $title = "NoConnectionException";
  protected $message = "";

    public function __construct($message) {
        //Setzen der Nachricht der Exception
        $this->message = $message;
    }

    public function getTitle(){
      return $this->title;
    }
}
