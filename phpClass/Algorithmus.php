<?php
/*
Diese Klasse ist für die Berechnung des Algorithmus zuständig.

=> (Pro Stadtteil)

kinder3b6 * %die Kita gehen * %Anteil andere Stadtteile
-----------------------------------------------------s
                    Kitaplätze

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
    // var_dump($sql_1bis4);

      while($rowKapa = $sql_kapa->fetch_assoc() ){
        echo "<br>";
        $stadtteilKapa = $rowKapa["Stadtteil"];
        $kapa = $rowKapa["Kapa"];
        echo $stadtteilKapa . "_Kapazität: " . $kapa;

        while($rowKinder = $sql_3bis6->fetch_assoc() ){

            echo "<br>";
            $stadtteilKinder = $rowKinder["Stadtteil_Bez"];
            $kinder = $rowKinder["SummeKinder"];
            echo $stadtteilKinder . "_Kinder: " . $kinder;

        }
      }



        // $Stadtteil = $rowKapa["Stadtteil"];
        // echo $Stadtteil;

        // if
        // echo $Stadtteil;
        // // echo $row["Kapa"];
        // echo "<br />";






    //   while($row = $sql_1bis4->fetch_assoc() ){
    //    echo $row["Stadtteil_Bez"];
    //    echo $row["SummeKinder"];
    //    echo "<br />";
    //  }

    if (!$sql_kapa) {
      echo "Konnte Abfrage nicht erfolgreich ausführen von DB: " . mysql_error();
      exit;
    }
    else{
      // echo "<br />";
      // echo "<br />";
      //  while($row = $sql_kapa->fetch_assoc() ){
      //   echo $row["Stadtteil"];
      //   echo $row["Kapa"];
      //   echo "<br />";
      // }
    }


    // Architektur der Ausgabe
    $prognoseAusgabe = array(
      "Stadtteil1" => array(84.99,70,80),
      "Stadtteil2" => array(85,12,14),
      "Stadtteil3" => array(105,120,140),
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
    // $cl_DatenBankabfrage = new Datenbankabfrage();
    // $kapazi = $cl_DatenBankabfrage->getKapazitaetProStadtteil("Buer");
    // while ($row = $kapazi->fetch_assoc()) {
    //   $this->kapa = $row['Kapa'];
    // }
    // $this->kapa = $this->kapa+200000;
    // echo $this->kapa;
  }


}


?>
