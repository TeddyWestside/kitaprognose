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
    $sql_0bis3 = $cl_DatenBankabfrage->getAnzahlKinder0bis3();
    $ar_kapa;
    $ar_3bis6;
    $ar_2bis5;
    $ar_1bis4;
    $ar_0bis3;

    while($row = $sql_kapa->fetch_assoc()){
      $ar_kapa[] = $row;
    }
    while($row = $sql_3bis6->fetch_assoc()){
      $ar_3bis6[] = $row;
    }
    while($row = $sql_2bis5->fetch_assoc()){
      $ar_2bis5[] = $row;
    }
    while($row = $sql_1bis4->fetch_assoc()){
      $ar_1bis4[] = $row;
    }
    while($row = $sql_0bis3->fetch_assoc()){
      $ar_0bis3[] = $row;
    }
    $ar_m0bis2 = $ar_0bis3;

    // Prognose +1 Jahr
    foreach($ar_kapa as $kapa){
      foreach($ar_3bis6 as $kinder){
        if($kapa["Stadtteil"] == $kinder["Stadtteil"]){
          if($kapa["Kapa"] == 0){
            $auslastung = 0;
          }
          else{
            $auslastung = $propChildren*$kinder["SummeKinder"]/$kapa["Kapa"];
          }
          $prognoseAusgabe[$kapa["Stadtteil"]][] = round($auslastung, 2);

        }
      }
    }
    // Prognose +2 Jahr
    foreach($ar_kapa as $kapa){
      foreach($ar_2bis5 as $kinder){
        if($kapa["Stadtteil"] == $kinder["Stadtteil"]){
          if($kapa["Kapa"] == 0){
            $auslastung = 0;
          }
          else{
            $auslastung = $propChildren*$kinder["SummeKinder"]/$kapa["Kapa"];
          }
          $prognoseAusgabe[$kapa["Stadtteil"]][] = round($auslastung, 2);

        }
      }
    }

    // Prognose +3 Jahr
    foreach($ar_kapa as $kapa){
      foreach($ar_1bis4 as $kinder){
        if($kapa["Stadtteil"] == $kinder["Stadtteil"]){
          if($kapa["Kapa"] == 0){
            $auslastung = 0;
          }
          else{
            $auslastung = $propChildren*$kinder["SummeKinder"]/$kapa["Kapa"];
          }
          $prognoseAusgabe[$kapa["Stadtteil"]][] = round($auslastung, 2);

        }
      }
    }

    // Prognose +4 Jahr
    foreach($ar_kapa as $kapa){
      foreach($ar_0bis3 as $kinder){
        if($kapa["Stadtteil"] == $kinder["Stadtteil"]){
          if($kapa["Kapa"] == 0){
            $auslastung = 0;
          }
          else{
            $auslastung = $propChildren*$kinder["SummeKinder"]/$kapa["Kapa"];
          }
          $prognoseAusgabe[$kapa["Stadtteil"]][] = round($auslastung, 2);

        }
      }
    }

    // Prognose +5 Jahr
    foreach($ar_kapa as $kapa){
      foreach($ar_m0bis2 as $kinder){
        if($kapa["Stadtteil"] == $kinder["Stadtteil"]){
          if($kapa["Kapa"] == 0){
            $auslastung = 0;
          }
          else{
            $auslastung = $birthrate*$propChildren*$kinder["SummeKinder"]/$kapa["Kapa"];
          }
          $prognoseAusgabe[$kapa["Stadtteil"]][] = round($auslastung, 2);

        }
      }
    }

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

    // Architektur der Ausgabe
    // $prognoseAusgabe = array(
    //   "Stadtteil1" => array(84.99,70,80,22,22),
    //   "Stadtteil2" => array(85,12,14),
    //   "Stadtteil3" => array(105,100,140),
    //   "Stadtteil4" => array(115,70,80),
    //   "Stadtteil5" => array(115.01,70,80),
    // );

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
