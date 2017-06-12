<?php

/*  Diese Klasse ist für die Herstelung der Verbindung
*   zur Datenbank zuständig.
*/
echo "Verbindungsaufbau begonnen";

$servername = "localhost";
$username = "root";
$password = "";

// Connection erstellen
$conn = new mysqli($servername, $username, $password);
mysqli_query($conn, "SET NAMES 'utf8'");

// Connection prüfen
if ($conn->connect_error) {
  die("<br> Verbindung fehlgeschlagen: " . $conn->connect_error);
}
else {
  echo "<br> Verbindung hergestellt";
}


?>
