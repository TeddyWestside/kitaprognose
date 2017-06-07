<<?php
/** Diese Klasse ist für das ziehen der erfolderlichen Daten aus der Datenbank
*   zuständig.
*/


// Datenbank Zugangsdaten

echo "<p>util_Datenbank ist eingebungen<p>";

$servername = "localhost";
$username = "root";
$password = "";

// Datenbank-Queries

$sql_CreateDB = "CREATE DATABASE IF NOT EXISTS CarstenTestDB";
$sql_CreateTableKita = "CREATE TABLE IF NOT EXISTS CarstenTestDB.Kitas (
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

// $sql_CreateTableKita = "CREATE TABLE IF NOT EXISTS CarstenTestDB.AlterStadtteil (
//   Stichtag date,
//   Bezirk_id int,
//   Art varchar(50),
//   Betriebsnummer varchar(40)
// )";


// Connection erstellen
$conn = new mysqli($servername, $username, $password);
// Connection prüfen
if ($conn->connect_error) {
  die("<br> Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Datenbank erstellen
if ($conn->query($sql_CreateDB) === TRUE) {
  echo "<br> Datenbank erfolgreich erstellt";
} else {
  echo "<br> Error Datenbankerstellung: " . $conn->error;
}

// Tabelle "Kitas" erstellen
if ($conn->query($sql_CreateTableKita) === TRUE) {
  echo "<br> Tabelle 'Kitas' erfolgreich erstellt";
} else {
  echo "<br> Error Erstellung Tabelle 'Kitas': " . $conn->error;
}






$conn->close();


?>
