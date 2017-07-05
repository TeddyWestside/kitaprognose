<?php

/*  Diese Klasse ist für die Herstelung der Verbindung
*   zur Datenbank zuständig.
    @author Carsten Schober
*/

$servername = "localhost";
$username = "root";
$password = "";

// Connection erstellen
$GLOBALS['conn'] = new mysqli($servername, $username, $password);
mysqli_query($GLOBALS['conn'], "SET NAMES 'utf8'");

// Connection prüfen
if ($GLOBALS['conn']->connect_error) {
  die("<br> Verbindung fehlgeschlagen: " . $conn->connect_error);
}


?>
