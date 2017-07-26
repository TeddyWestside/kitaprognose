<?php
/*  Die NoDatabaseException ist eine benutzerdefinierte Exceptinon,
    die geworfen wird, wenn keine Datenbank existiert
    @author Carsten Schober
*/
class NoDatabaseException extends Exception
{
  protected $title = "NoDatabaseException";
  protected $message = "";

    public function __construct($message) {
        //Setzen der Nachricht der Exception
          $this->message = $message;
    }

    public function getTitle(){
      return $this->title;
    }
}
