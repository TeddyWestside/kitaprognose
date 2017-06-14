<?php
/*  Diese Klasse ist für das Einrichten der Datenbank und
*   für die Erstellung der erforderlichen Tabellen zuständig.
    @author Carsten Schober
*/
class Datenbankinitialisierung{
  // Datenbank-Queries

  private $sql_CreateDB = "CREATE DATABASE IF NOT EXISTS Kitaprognose DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
  private $sql_CreateTableKita = "CREATE TABLE IF NOT EXISTS Kitaprognose.Kitas (
    Id int,
    Name varchar(100),
    Art varchar(50),
    Traeger varchar(50),
    PLZ varchar(5),
    Ort varchar(50),
    Strasse varchar(100),
    Bezirk varchar(50),
    Stadtteil varchar(50),
    X int,
    Y int,
    Telefon varchar(30),
    Fax varchar(30),
    EMail varchar(100),
    Internet varchar (255),
    Info varchar(255),
    Internetbeschreibung varchar (255),
    Barrierefrei_Inklusion varchar(10),
    Anzahl_der_Plaetze int,
    Anzahl_der_Gruppen int,
    Betriebsnummer varchar(40)
  )";

  private $sql_CreateTableAlterStadtteil = "CREATE TABLE IF NOT EXISTS Kitaprognose.AlterStadtteil (
    Stichtag date,
    Bezirk_id int,
    Bezirk_Bez varchar(50),
    Stadtteil_Id int,
    Stadtteil_Bez varchar(50),
    0bis1m int,
    0bis1w int,
    1bis2m int,
    1bis2w int,
    2bis3m int,
    2bis3w int,
    3bis4m int,
    3bis4w int,
    4bis5m int,
    4bis5w int,
    5bis6m int,
    5bis6w int,
    6bis7m int,
    6bis7w int,
    7bis8m int,
    7bis8w int,
    8bis9m int,
    8bis9w int,
    9bis10m int,
    9bis10w int,
    Gesamtstadt int
  )";

  // Datenbank erstellen
  public function erstelleDatenbank() {
    if ($GLOBALS['conn']->query($this->sql_CreateDB) != TRUE) {
      echo "<br> Error Datenbankerstellung: " . $GLOBALS['conn']->error;
    }
  }

  // Tabelle "Kitas" erstellen
  public function erstelleTabelleKitas() {
    if ($GLOBALS['conn']->query($this->sql_CreateTableKita) != TRUE) {
      echo "<br> Error Erstellung Tabelle 'Kitas': " . $GLOBALS['conn']->error;
    }
  }

  // Tabelle "AlterStadtteil" erstellen
  public function erstelleTabelleAlterStadtteil() {
    if ($GLOBALS['conn']->query($this->sql_CreateTableAlterStadtteil) != TRUE) {
      echo "<br> Error Erstellung Tabelle 'AlterStadtteil': " . $GLOBALS['conn']->error;
    }
  }

}
?>
