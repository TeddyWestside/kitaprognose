<?php
/*  Die NoConnectionException ist eine benutzerdefinierte Exceptinon
    für eine nicht vorhande Verbindung
    @author Carsten Schober
*/
class NoDataException extends Exception
{
  protected $title = "NoDataException";
  protected $message = "";

    public function __construct($message) {
        //Setzen der Nachricht der Exception
          $this->message = $message;
    }

    public function getTitle(){
      return $this->title;
    }
}
