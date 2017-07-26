<?php

/*  Diese Klasse ist für die Herstelung der Verbindung
*   zur Datenbank zuständig.
  @author Carsten Schober
*/

//Konfiguration laden
$ar_config = require "config.php";
$GLOBALS["config"] = $ar_config;

$config = $GLOBALS["config"];         //Einbinden des config-Array für allgemeingültige Werte
$servername = $config["servername"];    //Laden des Servernamen aus der config-Datei
$username = $config["username"];        //Laden des Usernamen aus der config-Datei
$password = $config["password"];        //Laden des Passworts aus der config-Datei

/*Durch diese Einstellungen werden bei Fehlern der mysql-Verbindung Exceptions anstelle
von PHP Warnungen geworfen, die anschließend gefangen werden können */
mysqli_report(MYSQLI_REPORT_STRICT);

//Setzen der Verbindung zur Datenbank
try {
  $GLOBALS['conn'] = new mysqli($servername, $username, $password);
  //Ändern des Character Sets zu utf8
  $GLOBALS['conn']->set_charset("utf8");
}

//Fangen der Exception, die durch einen Fehler bei der Verbindung zum Datenbankserver auftritt
catch (Exception $e) {
  // Weiterwerfen der Exception, da es sich bei dieser Exception um eine NoConnectionException handeln soll
  throw new NoConnectionException($lang->Error->NoConnectionExceptionDBServer);
}

//Fangen der Exception, die durch durch den obigen Fehler geworfen wird

?>
