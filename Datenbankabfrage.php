<?php
/*  Diese Klasse ist für das ziehen der erfolderlichen Daten aus der Datenbank
    zuständig.
*/
class Datenbankabfrage
{

  public function getKitasInStadtteil($Stadtteil)
  {
    global $conn;
    $sql_KitasImStadtteil = $conn->query("SELECT * FROM Kitaprognose.Kitas WHERE Stadtteil LIKE '" . $Stadtteil . "'");
    return $sql_KitasImStadtteil;
  }

  public function getKapazitaetProKita($Kita)
  {
    global $conn;
    $sql_KapazitaetInKita = $conn->query("SELECT Anzahl_der_Plaetze FROM Kitaprognose.Kitas WHERE Name LIKE '" . $Kita . "'");

    return $sql_KapazitaetInKita;
  }
}


?>
