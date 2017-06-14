<?php

// Mit dieser Php-Datei wird der Prozess der Db-Erstellung angestoßen
// @author Carsten Schober


require 'phpClass/Datenbankinitialisierung.php';
require 'phpClass/Algorithmus.php';

$cl_DBInit = new Datenbankinitialisierung;
$cl_Algorithmus = new Algorithmus;

$cl_DBInit->erstelleDatenbank();
$cl_DBInit->erstelleTabelleKitas();
$cl_DBInit->erstelleTabelleAlterStadtteil();
$ergebnise=$cl_Algorithmus->getPrognose(2.5,3.6);

?>
