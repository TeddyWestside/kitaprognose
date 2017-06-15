<?php
/*  Diese Klasse ist für das ziehen der erfolderlichen Daten aus der Datenbank
    zuständig.
    @author Carsten Schober
*/
class Datenbankabfrage
{
  public function getKitasInStadtteil($Stadtteil)
  {
    $sql_KitasImStadtteil = $GLOBALS['conn']->query("SELECT * FROM Kitaprognose.Kitas WHERE Stadtteil LIKE '" . $Stadtteil . "'");
    return $sql_KitasImStadtteil;
  }

  public function getKapazitaetProKita($Kita)
  {
    $sql_KapazitaetInKita = $GLOBALS['conn']->query("SELECT Anzahl_der_Plaetze FROM Kitaprognose.Kitas WHERE Name LIKE '" . $Kita . "'");
    return $sql_KapazitaetInKita;
  }

  public function getAnzahlKinder3bis6($AnzahlKinder3bis6)
  {
      $sql_AnzahlKinder3bis6 = $GLOBALS['conn']->query("SELECT Stadtteil_Bez, Stichtag, Bezirk_Bez, (sum(5bis6m) + sum(5bis6w) + sum(4bis5m) + sum(4bis5w + sum(3bis4m) + sum(3bis4w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez LIKE 'Gelsenkirchen' AND Stichtag LIKE '30.06.16' GROUP BY Stadtteil_Bez");
      return $sql_AnzahlKinder3bis6;
  }
  public function getAnzahlKinder2bis5($AnzahlKinder2bis5)
  {
      $sql_AnzahlKinder3bis6 = $GLOBALS['conn']->query("SELECT Stadtteil_Bez, Stichtag, Bezirk_Bez, (sum(2bis3m) + sum(2bis3w) + sum(4bis5m) + sum(4bis5w + sum(3bis4m) + sum(3bis4w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez LIKE 'Gelsenkirchen' AND Stichtag LIKE '30.06.16' GROUP BY Stadtteil_Bez");
      return $sql_AnzahlKinder2bis5;
  }
  public function getAnzahlKinder1bis4($AnzahlKinder1bis4)
  {
      $sql_AnzahlKinder3bis6 = $GLOBALS['conn']->query("SELECT Stadtteil_Bez, Stichtag, Bezirk_Bez, (sum(2bis3m) + sum(2bis3w) + sum(1bis2m) + sum(1bis2w + sum(3bis4m) + sum(3bis4w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez LIKE 'Gelsenkirchen' AND Stichtag LIKE '30.06.16' GROUP BY Stadtteil_Bez");
      return $sql_AnzahlKinder1bis4;
  }
  public function getAnzahlKinder0bis6($AnzahlKinder0bis3)
  {
      $sql_AnzahlKinder0bis3 = $GLOBALS['conn']->query("SELECT Stadtteil_Bez, Stichtag, Bezirk_Bez, (sum(2bis3m) + sum(2bis3w) + sum(1bis2m) + sum(1bis2w + sum(0bis1m) + sum(0bis1w)) as SummeKinder FROM Kitaprognose.AlterStadtteil WHERE Bezirk_Bez LIKE 'Gelsenkirchen' AND Stichtag LIKE '30.06.16' GROUP BY Stadtteil_Bez");
      return $sql_AnzahlKinder0bis3;
  }
}


?>
