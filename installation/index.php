<?php
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

//Einbinden der Connection.php für die Verbindung zur Datenbank


try {
  //Datenbank initialisieren
  $cl_Datenbankinitialisierung = new Datenbankinitialisierung();
  $cl_Datenbankinitialisierung->erstelleDatenbank();
  $cl_Datenbankinitialisierung->erstelleTabelleKitas();
  $cl_Datenbankinitialisierung->erstelleTabelleAlterStadtteil();
  $cl_Datenbankinitialisierung->erstelleTabelleZwischenspeicher();
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

if ($GLOBALS['conn']->query($sqlInsert0) === FALSE & $GLOBALS['conn']->query($sqlInsert1) === FALSE) {
    echo "Error: " . $GLOBALS['conn']->error;
}

$gr_datenbankabfrage = new Datenbankabfrage();
$zwischenspeicher = $gr_datenbankabfrage->getZwischenspeicher();

?>
