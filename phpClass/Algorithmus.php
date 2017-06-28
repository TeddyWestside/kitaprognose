<?php
/*
Diese Klasse ist für die Berechnung des Algorithmus zuständig.
@author Carsten Schober
*/

require 'Datenbankabfrage.php';

class Algorithmus
{
  public function getPrognose($propChildren,$birthrate){

    // $test = $this->cl_DatenBankabfrage->getAnzahlKinder3bis6();
    // $kinder0b3 = $this->cl_DatenBankabfrage->getAnzahlKinder3bis6();
    // var_dump($kinder0b3);

    // Durchführung des Algorithmus


    // $cl_DatenBankabfrage = $this->cl_DatenBankabfrage;
    // var_dump($cl_DatenBankabfrage->getKapazitaetProStadtteil("Buer"));
    //
    // $kapa = $cl_DatenBankabfrage->getKapazitaetProStadtteil("Buer");
    // while ($row = $kapa->fetch_assoc()) {
    //   echo $row['Kapa'];
    // }

    $cl_DatenBankabfrage = new Datenbankabfrage();
    // Kapazität eines STadteils herausfinden
    $sql_kapa = $cl_DatenBankabfrage->getKapazitaet();
    $sql_3bis6 = $cl_DatenBankabfrage->getAnzahlKinder3bis6();
    $sql_2bis5 = $cl_DatenBankabfrage->getAnzahlKinder2bis5();
    $sql_1bis4 = $cl_DatenBankabfrage->getAnzahlKinder1bis4();
    $ar_kapa;
    $ar_3bis6;
    $ar_2bis5;
    $ar_1bis4;

    while($row = $sql_kapa->fetch_assoc()){
      $ar_kapa[] = $row;
    }
    while($row = $sql_3bis6->fetch_assoc()){
      $ar_3bis6[] = $row;
    }

    foreach($ar_kapa as $kapa){
      foreach($ar_3bis6 as $kinder){
        if($kapa["Stadtteil"] == $kinder["Stadtteil"]){
          echo "Stadtteil: " . $kapa["Stadtteil"];
          echo " => Auslastung: " . $kinder["SummeKinder"] . "/" . $kapa["Kapa"];
          echo "<br>";
          $prognoseAusgabe[$kapa["Stadtteil"]] = array(1,2,3);

        }
      }
    }

    var_dump($prognoseAusgabe);
    $prognoseAusgabe = array(
      "Stadtteil1" => array(84.99,70,80),
      "Stadtteil2" => array(85,12,14),
      "Stadtteil3" => array(105,100,140),
      "Stadtteil4" => array(115,70,80),
      "Stadtteil5" => array(115.01,70,80),
    );
    echo "<br>";
    echo "<br>";
    echo "<br>";
    var_dump($prognoseAusgabe);

    // var_dump($prognoseAusgabe);
    echo "<br>";
    echo $prognoseAusgabe["Stadtteil1"][0];
    echo "<br>";
    $prognoseAusgabe["Stadtteil1"][0] = 111;
    echo "<br>";
    echo $prognoseAusgabe["Stadtteil1"][0];


    /**
    Wichtig!
    */
    // foreach($ar_kapa as $kapa){
    //   foreach($ar_3bis6 as $kinder){
    //     if($kapa["Stadtteil"] == $kinder["Stadtteil"]){
    //       echo "Stadtteil: " . $kapa["Stadtteil"];
    //       echo " => Auslastung: " . $kinder["SummeKinder"] . "/" . $kapa["Kapa"];
    //       echo "<br>";
    //     }
    //   }
    // }



    // foreach($ar_kapa as $kapa){
    //   echo "Durchlauf";
    //   echo "<br>";
    //   echo "kinderStadtteil: " . $kapa["Stadtteil"];
    //   echo "kinderAnzahl: " . $kapa["SummeKinder"];
    //   // foreach($ar_kapa as $kapaStadtteil => $kapaAnzahl){
    //   echo "innere Schleife";
    //   echo "<br />";
    //   echo "kapaStadtteil: " . $kapaStadtteil;
    //   echo "kapaAnzahl: " . $kapaAnzahl;
    //   echo "<br />";
    // }
    // }

    // if (!$sql_kapa) {
    //   echo "Konnte Abfrage nicht erfolgreich ausführen von DB: " . mysql_error();
    //   exit;
    // }
    // else{
    //   echo "<br />";
    //   echo "<br />";
    //    while($row = $sql_kapa->fetch_assoc() ){
    //     echo $row["Stadtteil"];
    //     echo $row["Kapa"];
    //     echo "<br />";
    //   }
    // }


    // Architektur der Ausgabe
    $prognoseAusgabe = array(
      "Stadtteil1" => array(84.99,70,80,22,22),
      "Stadtteil2" => array(85,12,14),
      "Stadtteil3" => array(105,100,140),
      "Stadtteil4" => array(115,70,80),
      "Stadtteil5" => array(115.01,70,80),
    );

    return $prognoseAusgabe;
  }

  public function closeConnection(){
    $GLOBALS['conn']->close();
  }

  public function __construct()
  {

  }


}


?>
