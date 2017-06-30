<?php
/*  Diese Klasse ist für das ziehen der erfolderlichen Daten aus der Datenbank
    zuständig.
    @author Carsten Schober und Ken Diepers
*/

require 'Datenbankinitialisierung.php';

class Datenbankabfrage
{
  // // Funktion, die die Kitas für einen Stadtteil in Gelsenkirchen zurückliefert.
  // public function getKitasInStadtteil($Stadtteil)
  // {
  //   $sql_KitasImStadtteil = $GLOBALS['conn']->query("SELECT * FROM Kitaprognose.Kitas WHERE Stadtteil LIKE '" . $Stadtteil . "'");
  //   return $sql_KitasImStadtteil;
  // }

  // // Funktion, die die Kapazität für eine Kita zurückliefert.
  // public function getKapazitaetProKita($Kita)
  // {
  //   $sql_KapazitaetInKita = $GLOBALS['conn']->query("SELECT Anzahl_der_Plaetze FROM Kitaprognose.Kitas WHERE Name LIKE '" . $Kita . "'");
  //   return $sql_KapazitaetInKita;
  // }

  function __construct() {
    $cl_Datenbankinitialisierung = new Datenbankinitialisierung();
    $cl_Datenbankinitialisierung->erstelleDatenbank();
    $cl_Datenbankinitialisierung->erstelleTabelleKitas();
    $cl_Datenbankinitialisierung->erstelleTabelleAlterStadtteil();
  }


  // Funktion, die die aufsummierte Kapazität der Kitas für ein Stadtteil zurückliefert.
  public function getKapazitaet()
  {
    $sql_Kapazitaet = $GLOBALS['conn']->query("SELECT Stadtteil ,sum(Anzahl_der_Plaetze) as Kapa FROM Kitaprognose.Kitas Group by Stadtteil;");
    // var_dump($sql_KapazitaetInStadtteil->fetch_assoc());
    return $sql_Kapazitaet;
  }

  // Funktion, die die Anzahl der Kinder im mitgegebenen Stadtteil zwischen 3 und 6 in Gelsenkirchen zurückliefert.
  public function getAnzahlKinder3bis6()
  {
      $sql_AnzahlKinder = $GLOBALS['conn']->query("SELECT Stadtteil_Bez as Stadtteil, (sum(5bis6m) + sum(5bis6w) + sum(4bis5m) + sum(4bis5w) + sum(3bis4m) + sum(3bis4w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez NOT LIKE 'Gesamtstadt' AND Stichtag LIKE '2015-12-31' GROUP BY Stadtteil_Bez;");
      return $sql_AnzahlKinder;
  }
  // Funktion, die die Anzahl der Kinder im mitgegebenen Stadtteil zwischen 2 und 5 in Gelsenkirchen zurückliefert.
  public function getAnzahlKinder2bis5()
  {
      $sql_AnzahlKinder = $GLOBALS['conn']->query("SELECT Stadtteil_Bez as Stadtteil, (sum(2bis3m) + sum(2bis3w) + sum(4bis5m) + sum(4bis5w) + sum(3bis4m) + sum(3bis4w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez NOT LIKE 'Gesamtstadt' AND Stichtag LIKE '2015-12-31' GROUP BY Stadtteil_Bez;");
      return $sql_AnzahlKinder;
  }
    // Funktion, die die Anzahl der Kinder im mitgegebenen Stadtteil zwischen 1 und 4 in Gelsenkirchen zurückliefert.
  public function getAnzahlKinder1bis4()
  {
      $sql_AnzahlKinder = $GLOBALS['conn']->query("SELECT Stadtteil_Bez as Stadtteil, (sum(2bis3m) + sum(2bis3w) + sum(1bis2m) + sum(1bis2w) + sum(3bis4m) + sum(3bis4w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez NOT LIKE 'Gesamtstadt' AND Stichtag LIKE '2015-12-31' GROUP BY Stadtteil_Bez;");
      return $sql_AnzahlKinder;
  }
    // Funktion, die die Anzahl der Kinder im mitgegebenen Stadtteil zwischen 0 und 3 in Gelsenkirchen zurückliefert.
  public function getAnzahlKinder0bis3()
  {
      $sql_AnzahlKinder = $GLOBALS['conn']->query("SELECT Stadtteil_Bez as Stadtteil, (sum(2bis3m) + sum(2bis3w) + sum(1bis2m) + sum(1bis2w) + sum(0bis1m) + sum(0bis1w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez NOT LIKE 'Gesamtstadt' AND Stichtag LIKE '2015-12-31' GROUP BY Stadtteil_Bez;");
      return $sql_AnzahlKinder;
  }
    // Funktion, die die Anzahl der Kinder im mitgegebenen Stadtteil zwischen 0 und 3 in Gelsenkirchen zurückliefert.
  public function getAnzahlKinder0bis6()
  {
      $sql_AnzahlKinder = $GLOBALS['conn']->query("SELECT Stadtteil_Bez as Stadtteil, (sum(2bis3m) + sum(2bis3w) + sum(1bis2m) + sum(1bis2w) + sum(0bis1m) + sum(0bis1w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez NOT LIKE 'Gesamtstadt' AND Stichtag LIKE '2015-12-31' GROUP BY Stadtteil_Bez;");
      return $sql_AnzahlKinder;
  }
}

?>
