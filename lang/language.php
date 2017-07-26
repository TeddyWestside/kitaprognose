<!--
author: Michael Kostka (https://www.foxplex.com/sites/mehrsprachige-webseiten-mit-php-und-json/)
description: Dient zum Laden der entsprechenden Sprachdatei und Rückgabe des Sprach-JSON. Sie wurde
kopiert und nur leicht modifiziert.
-->
<?php
//Die Language-Klasse ermöglicht das Übersetzten der sprachabhängigen Ausgaben in der GUI.
class Language {

  public $data;
  /*Im Konstruktor wird mit dem übergebenen Sprachparameter die entsprechende Sprachquelldatei
  im JSON-Format geöffnet. Die Datei muss im Ordner "lang" abgelegt sein. Anschließend wird
  der JSON Decodiert und in der public Variable data abgelegt.
  */
  function __construct($pfad) {
    $data = file_get_contents($pfad . ".json");
    $this->data = json_decode($data);
  }
  /* Durch den Aufruf der translate-Funktion wird die data-Variable zurückgegeben um den Zugriff aus
  Klassen zu ermöglichen.
  */
  function translate() {
    return $this->data;
  }
}
?>
