<?php
/*
    Diese Klasse ist für die Berechnung des Algorithmus zuständig.
*/

require 'Datenbankabfrage.php';

$cl_DatenBankabfrage = new Datenbankabfrage;

$Kitas = $cl_DatenBankabfrage->getKitasInStadtteil("Beckhausen");
$KapaKita = $cl_DatenBankabfrage->getKapazitaetProKita("Agnesstraße");
while ($row = $Kitas->fetch_assoc()) {
    echo "<br />--------------------<br />";
    echo $row['Id']."<br>";
    echo $row['Name']."<br>";
    // echo $row['Art']."<br>";
    // echo $row['Traeger']."<br>";
    // echo $row['PLZ']."<br>";
    // echo $row['Ort']."<br>";
    // echo $row['Strasse']."<br>";
    // echo $row['Bezirk']."<br>";
    // echo $row['Stadtteil']."<br>";
    // echo $row['X']."<br>";
    // echo $row['Y']."<br>";
    // echo $row['Telefon']."<br>";
    // echo $row['EMail']."<br>";
    // echo $row['Internet']."<br>";
    // echo $row['Info']."<br>";
    // echo $row['Internetbeschreibung']."<br>";
    // echo $row['Barrierefrei_Inklusion']."<br>";
    // echo $row['Anzahl_der_Plaetze']."<br>";
    // echo $row['Anzahl_der_Gruppen']."<br>";
    // echo $row['Betriebsnummer']."<br>";
  //  break; // Muss gelöscht werden! Für Demonstrationszwecke!
}
while ($row = $KapaKita->fetch_assoc()) {
    echo "<br />--------------------<br />";
    if ($row['Anzahl_der_Plaetze'] === NULL){
      echo "KEINE KAPAZITÄT";
    }
    else{
      echo $row['Anzahl_der_Plaetze']."<br>";
    }
}




// Fixe Werte für das Weiterarbeiten des Designs
$prognoseAusgabe = array(
    "Kita1" => "50%",
    "Kita2" => "75%",
    "Kita3" => "90%",
    "Kita4" => "125%",
);

// Ausgabe in der Liste für die Anzeige der Prognose
echo "<table class='striped'>
        <thead>
          <tr>
              <th>Kita</th>
              <th>Auslastung</th>
          </tr>
        </thead>";
foreach($prognoseAusgabe as $x => $y) {
    echo "<tr><td>" . $x . "</td><td>" . $y . "</td></tr>";
}
echo "</table>";


$conn->close();


 ?>
