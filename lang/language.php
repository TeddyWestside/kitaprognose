<?php
//Die Language-Klasse ermöglicht das Übersetzten der sprachabhängigen Ausgaben in der GUI.
class language {

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
