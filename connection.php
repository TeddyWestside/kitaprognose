<?php

/*  Diese Klasse ist für die Herstelung der Verbindung
*   zur Datenbank zuständig.
    @author Carsten Schober
*/

// $servername = "localhost";
// $username = "root";
// $password = "";
$config = include "config.php";
$servername = $config["servername"];
$username = $config["username"];
$password = $config["password"];


// Connection erstellen
// $GLOBALS['conn'] = new mysqli($servername, $username, $password);
// mysqli_query($GLOBALS['conn'], "SET NAMES 'utf8'");
//
// // Connection prüfen
// if ($GLOBALS['conn']->connect_error) {
//   die("<br> Verbindung fehlgeschlagen: " . $conn->connect_error);
// }



mysqli_report(MYSQLI_REPORT_STRICT);
try {
    $GLOBALS['conn'] = new mysqli($servername, $username, $password);
} catch (Exception $e) {
    $GLOBALS['conn'] = NULL;
    echo 'Datenbankerror: ' . $e->getMessage() . "<br />";
}



?>
