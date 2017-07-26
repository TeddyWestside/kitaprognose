<?php

/*  Diese Klasse ist für die Installation der Webapplikation zuständig
    inklusive Erstellen der Datenbank, Tabellen und das Befüllen Dieser.
    @author Carsten Schober
*/

//Hinzufügen der für die Installation benötigten Klassen
require "../phpClass/Datenbankinitialisierung.php";
require "../phpClass/Datenbereitstellung.php";
require "../phpClass/Datenbankabfrage.php";
require "../lang/Language.php";
require "../connection.php";
require "../exceptions/NoConnectionException.php";
require "../exceptions/NoDatabaseException.php";
require "../exceptions/NoDataException.php";

//Sql-Statements definieren
$sqlInsert0 = "INSERT INTO kitaprognose.Zwischenspeicher (ID, BESCHREIBUNG, WERT) VALUES (0,'','') ON DUPLICATE KEY UPDATE WERT=WERT";
$sqlInsert1 = "INSERT INTO kitaprognose.Zwischenspeicher (ID, BESCHREIBUNG, WERT) VALUES (1,'','') ON DUPLICATE KEY UPDATE WERT=WERT";

//Konfiguration laden
$ar_config = require "../config.php";
$GLOBALS["config"] = $ar_config;

//Sprache laden
$cl_lang = new Language("../lang/".$ar_config["langCode"]);
$GLOBALS["lang"] = $cl_lang->translate();


try {
  //Datenbank initialisieren
  $cl_Datenbankinitialisierung = new Datenbankinitialisierung();
  $cl_Datenbankinitialisierung->erstelleDatenbank();
  $cl_Datenbankinitialisierung->erstelleTabelleKitas();
  $cl_Datenbankinitialisierung->erstelleTabelleAlterStadtteil();
  $cl_Datenbankinitialisierung->erstelleTabelleZwischenspeicher();
  if ($GLOBALS['conn']->query($sqlInsert0) === FALSE & $GLOBALS['conn']->query($sqlInsert1) === FALSE) {
      echo "Error: " . $GLOBALS['conn']->error;
  }
  echo "Datenbank erstellt!";
  echo "<br>";
} catch (Exception $e) {
  echo $e->getMessage()."<br>";
}

//Datenbank befüllen
try {
  $gr_datenbereitstellung = new Datenbereitstellung();
  $gr_datenbereitstellung->set_fehlende_kapazitaeten("../files/Fehlende_Kapazitaeten.csv");
  $gr_datenbereitstellung->initialisiere_datenbestand();
  echo "Datenbank wurde befüllt!";
  echo "<br>";
} catch (Exception $e) {
  echo $e->getMessage()."<br>";
}

?>
