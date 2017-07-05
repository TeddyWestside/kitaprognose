<?php
require "Datenbereitstellung.php";

include "../connection.php";

//Datenbank initialisieren
$cl_Datenbankinitialisierung = new Datenbankinitialisierung();
$cl_Datenbankinitialisierung->erstelleDatenbank();
$cl_Datenbankinitialisierung->erstelleTabelleKitas();
$cl_Datenbankinitialisierung->erstelleTabelleAlterStadtteil();
echo "Datenbank erstellt!";
echo "<br>";

//Datenbank befüllen
$gr_datenbereitstellung = new Datenbereitstellung();
$gr_datenbereitstellung->aktualisiere_datenbestand();
echo "Datenbank wurde befüllt!";
echo "<br>";
?>
