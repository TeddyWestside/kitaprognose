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
}


?>
