<?php

/*
Diese Klasse ist für die Berechnung des Algorithmus zuständig.

*/

echo "<br /><br />" . "Testausabe aus der DB: " . "<br />";


// Abfrage der Datenbanktabelle "Kitas"
$ergebnis = $conn->query("SELECT * FROM Kitaprognose.Kitas");

while ($row = $ergebnis->fetch_assoc()) {
    echo $row['Id']."<br>";
    echo $row['Name']."<br>";
    echo $row['Art']."<br>";
    echo $row['Traeger']."<br>";
    echo $row['PLZ']."<br>";
    echo $row['Ort']."<br>";
    echo $row['Strasse']."<br>";
    echo $row['X']."<br>";
    echo $row['Y']."<br>";
    echo $row['Telefon']."<br>";
    echo $row['EMail']."<br>";
    echo $row['Internet']."<br>";
    echo $row['Info']."<br>";
    echo $row['Internetbeschreibung']."<br>";
    echo $row['Barrierefrei_Inklusion']."<br>";
    echo $row['Anzahl_der_Plaetze']."<br>";
    echo $row['Anzahl_der_Gruppen']."<br>";
    echo $row['Betriebsnummer']."<br>";
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
