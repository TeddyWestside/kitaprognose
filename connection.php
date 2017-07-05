<?php

/*  Diese Klasse ist für die Herstelung der Verbindung
*   zur Datenbank zuständig.
@author Carsten Schober
*/

require "exceptions/NoConnectionException.php";
require "exceptions/NoDatabaseException.php";
require "exceptions/NoDataException.php";

$servername = "localhost";
$username = "root";
$password = "";
$GLOBALS['conn'] = NULL;


try{
  @$db = new mysqli($servername, $username, $password);

  // Verbindung überprüfen
  if (mysqli_connect_errno()) {
    throw new NoConnectionException ("Keine Verbindung zur Datenbank.");
  }
  else{
    echo "test";
    $GLOBALS['conn'] = new mysqli($servername,$username,$password);
  }
}
catch (NoConnectionException $e){
    echo $e->getMessage() . " Fehlermeldung: " . mysqli_connect_error();
}

// function connect($server, $usr, $pass)
// {
//   try{
//     $GLOBALS['conn'] = new mysqli($server, $usr, $pass);
//   }
//   catch(NoConnectionException $e){
      // throw $e;
//   }
// }
//
// try {
//   connect($servername, $username, $password);
// }
//
// catch (NoConnectionException $e) {
//   echo $e->getTitle() . ": " . $e->getMessage();
// }


?>
