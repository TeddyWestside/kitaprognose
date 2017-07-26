<?php
require "../phpClass/Datenbankinitialisierung.php";
require "../phpClass/Datenbereitstellung.php";
require "../connection.php";
require "../lang/Language.php";

//Konfiguration laden
$ar_config = require "../config.php";
$GLOBALS["config"] = $ar_config;

//Sprache laden
$cl_lang = new Language("../lang/".$ar_config["langCode"]);
$cl_lang->translate();
$GLOBALS["lang"] = $cl_lang;

//Datenbank initialisieren
$cl_Datenbankinitialisierung = new Datenbankinitialisierung();
$cl_Datenbankinitialisierung->erstelleDatenbank();
$cl_Datenbankinitialisierung->erstelleTabelleKitas();
$cl_Datenbankinitialisierung->erstelleTabelleAlterStadtteil();
echo "Datenbank erstellt!";
echo "<br>";

//Datenbank befüllen
$gr_datenbereitstellung = new Datenbereitstellung();
$gr_datenbereitstellung->set_fehlende_kapazitaeten("../files/Fehlende_Kapazitaeten.csv");
$gr_datenbereitstellung->initialisiere_datenbestand();
echo "Datenbank wurde befüllt!";
echo "<br>";
?>
