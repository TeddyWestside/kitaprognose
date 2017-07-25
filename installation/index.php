<?php
require "../phpClass/Datenbankinitialisierung.php";
require "../phpClass/Datenbereitstellung.php";
require "../connection.php";

//Laden der Konfigurationsparameter, die zentral in der config.php festgelegt werden
$config = include "../config.php";
//Definierung der Standardsprache
$lang = $config["lang"];
//Einbindung der language.php Datei um Sprachunabhängigkeit in der GUI zu ermöglichen.
require ('../lang\language.php');
/*Instanzierung der language-Klasse und speichern der JSON-Variable in $lang um auf die Strings über
die IDs zugreifen zu können.
*/
$language = new language("../lang/".$lang);
$lang = $language->translate();


echo $lang->Main->label_birthrate;

//Datenbank initialisieren
$cl_Datenbankinitialisierung = new Datenbankinitialisierung();
$cl_Datenbankinitialisierung->erstelleDatenbank();
$cl_Datenbankinitialisierung->erstelleTabelleKitas();
$cl_Datenbankinitialisierung->erstelleTabelleAlterStadtteil();
echo "Datenbank erstellt!";
echo "<br>";

//Datenbank befüllen
$gr_datenbereitstellung = new Datenbereitstellung();
$gr_datenbereitstellung->initialisiere_datenbestand();
echo "Datenbank wurde befüllt!";
echo "<br>";
?>
