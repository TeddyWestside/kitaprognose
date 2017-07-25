<?php
/*  Diese Klasse ist für das ziehen der erfolderlichen Daten aus der Datenbank
    zuständig.
    @author Carsten Schober und Ken Diepers
*/

require 'Datenbankinitialisierung.php';

//Diese Klasse hält Methoden für die Datenbankabfrage
class Datenbankabfrage
{
  //Funktion, die die aufsummierte Kapazität der Kitas für ein Stadtteil zurückliefert
  public function getKapazitaet()
  {
    $sql_Kapazitaet = $GLOBALS['conn']->query("SELECT Stadtteil ,sum(Anzahl_der_Plaetze) as Kapa FROM Kitaprognose.Kitas Group by Stadtteil;");
    return $sql_Kapazitaet;
  }

  //Funktion, die das neuste Datum der Tabelle AlterStadtteil herausfinden und als String zurückliefert
  public function getNeusterDatensatzAlterStadtteil()
  {
      $neusterDatensatz;      // Hilfsvariable für die Ausgabe als String
      $ar_NeusterDatensatz;   // Hilfsarray für das Durchlaufen des Rückgabe-sql-Objekt
      $sql_NeusterDatensatz = $GLOBALS['conn']->query("SELECT Stichtag FROM Kitaprognose.AlterStadtteil ORDER BY Stichtag DESC LIMIT 1;");

      //Schleife, um mit dem Mysql-Objekt ein Array zu füllen
      while($row = $sql_NeusterDatensatz->fetch_assoc()){
        $ar_NeusterDatensatz[]= $row;
      }

      //Schleife, um aus dem ersten Feld des Arrays die String Variable zu füllen
      foreach($ar_NeusterDatensatz as $nD){
      $neusterDatensatz = $nD["Stichtag"];
      }
      return $neusterDatensatz;
  }

  //Funktion, die die Anzahl der Kinder zwischen 3 und 6 gruppiert nach Stadtteilen in Gelsenkirchen zurückliefert
  public function getAnzahlKinder3bis6()
  {
      $sql_AnzahlKinder = $GLOBALS['conn']->query("SELECT Stadtteil_Bez as Stadtteil, (sum(5bis6m) + sum(5bis6w) + sum(4bis5m) + sum(4bis5w) + sum(3bis4m) + sum(3bis4w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez NOT LIKE 'Gesamtstadt' AND Stichtag LIKE '" . $this->getNeusterDatensatzAlterStadtteil() . "' GROUP BY Stadtteil_Bez;");
      return $sql_AnzahlKinder;
  }
  //Funktion, die die Anzahl der Kinder zwischen 2 und 5 gruppiert nach Stadtteilen in Gelsenkirchen zurückliefert
  public function getAnzahlKinder2bis5()
  {
      $sql_AnzahlKinder = $GLOBALS['conn']->query("SELECT Stadtteil_Bez as Stadtteil, (sum(2bis3m) + sum(2bis3w) + sum(4bis5m) + sum(4bis5w) + sum(3bis4m) + sum(3bis4w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez NOT LIKE 'Gesamtstadt' AND Stichtag LIKE '" . $this->getNeusterDatensatzAlterStadtteil() . "' GROUP BY Stadtteil_Bez;");
      return $sql_AnzahlKinder;
  }
    //Funktion, die die Anzahl der Kinder zwischen 1 und 4 gruppiert nach Stadtteilen in Gelsenkirchen zurückliefert
  public function getAnzahlKinder1bis4()
  {
      $sql_AnzahlKinder = $GLOBALS['conn']->query("SELECT Stadtteil_Bez as Stadtteil, (sum(2bis3m) + sum(2bis3w) + sum(1bis2m) + sum(1bis2w) + sum(3bis4m) + sum(3bis4w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez NOT LIKE 'Gesamtstadt' AND Stichtag LIKE '" . $this->getNeusterDatensatzAlterStadtteil() . "' GROUP BY Stadtteil_Bez;");
      return $sql_AnzahlKinder;
  }
    //Funktion, die die Anzahl der Kinder zwischen 0 und 3 gruppiert nach Stadtteilen in Gelsenkirchen zurückliefert
  public function getAnzahlKinder0bis3()
  {
      $sql_AnzahlKinder = $GLOBALS['conn']->query("SELECT Stadtteil_Bez as Stadtteil, (sum(2bis3m) + sum(2bis3w) + sum(1bis2m) + sum(1bis2w) + sum(0bis1m) + sum(0bis1w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez NOT LIKE 'Gesamtstadt' AND Stichtag LIKE '" . $this->getNeusterDatensatzAlterStadtteil() . "' GROUP BY Stadtteil_Bez;");
      return $sql_AnzahlKinder;
  }
    //Funktion, die die Anzahl der Kinder zwischen 0 und 3 gruppiert nach Stadtteilen in Gelsenkirchen zurückliefert
  public function getAnzahlKinder0bis6()
  {
      $sql_AnzahlKinder = $GLOBALS['conn']->query("SELECT Stadtteil_Bez as Stadtteil, (sum(2bis3m) + sum(2bis3w) + sum(1bis2m) + sum(1bis2w) + sum(0bis1m) + sum(0bis1w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez NOT LIKE 'Gesamtstadt' AND Stichtag LIKE '" . $this->getNeusterDatensatzAlterStadtteil() . "' GROUP BY Stadtteil_Bez;");
      return $sql_AnzahlKinder;
  }
}

?>
