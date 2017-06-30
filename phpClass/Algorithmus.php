<?php
/*
Diese Klasse ist für die Berechnung des Algorithmus zuständig.
@author Carsten Schober
*/

require 'Datenbankabfrage.php';

class Algorithmus
{
  public function getPrognose($propChildren,$birthrate){

    // Erzeugen einer Instanz der KLasse Datenbankabfrage.
    $cl_DatenBankabfrage = new Datenbankabfrage();

    // Hilfsarrays für Schleifen
    $ar_kapa;
    $ar_3bis6;
    $ar_2bis5;
    $ar_1bis4;
    $ar_0bis3;

    try{
      // Kapazität eines jeden Stadteils herausfinden.
      $sql_kapa = $cl_DatenBankabfrage->getKapazitaet();

      // Anzahl der Kinder eine Altersklasse herausfinden.
      $sql_3bis6 = $cl_DatenBankabfrage->getAnzahlKinder3bis6();
      $sql_2bis5 = $cl_DatenBankabfrage->getAnzahlKinder2bis5();
      $sql_1bis4 = $cl_DatenBankabfrage->getAnzahlKinder1bis4();
      $sql_0bis3 = $cl_DatenBankabfrage->getAnzahlKinder0bis3();

      // Fehlerhandling, wenn Tabellen in der DB oder die Datenbank selbst fehlt.
      if($sql_kapa == NULL | $sql_3bis6 == NULL | $sql_2bis5 == NULL | $sql_1bis4 == NULL | $sql_0bis3 == NULL){
        throw new Exception('Datenbankfehler: Keine Datenbank oder Tabellen in der Datenbank.');
      }

      // Fehlerhandling für einen leeren Datensatz.
      if($sql_kapa->num_rows == 0 | $sql_3bis6->num_rows == 0 | $sql_2bis5->num_rows == 0 | $sql_1bis4->num_rows == 0 | $sql_0bis3->num_rows == 0){
        throw new Exception('Datenladefehler: Keine Daten in der Datenbank.');
      }
    }
    // Fangen der Exceptions und
    catch (Exception $e){
      echo $e->getMessage();
      return;
    }

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
            $prognoseAusgabe[$kapa["Stadtteil"]][] = 0;
          }
          else{
            $auslastung = $propChildren*$kinder["SummeKinder"]/$kapa["Kapa"];
            $prognoseAusgabe[$kapa["Stadtteil"]][] = round($auslastung, 2);
          }


        }
      }
    }
    // Prognose +2 Jahr
    foreach($ar_kapa as $kapa){
      foreach($ar_2bis5 as $kinder){
        if($kapa["Stadtteil"] == $kinder["Stadtteil"]){
          if($kapa["Kapa"] == 0){
            $prognoseAusgabe[$kapa["Stadtteil"]][] = 0;
          }
          else{
            $auslastung = $propChildren*$kinder["SummeKinder"]/$kapa["Kapa"];
            $prognoseAusgabe[$kapa["Stadtteil"]][] = round($auslastung, 2);
          }
        }
      }
    }

    // Prognose +3 Jahr
    foreach($ar_kapa as $kapa){
      foreach($ar_1bis4 as $kinder){
        if($kapa["Stadtteil"] == $kinder["Stadtteil"]){
          if($kapa["Kapa"] == 0){
            $prognoseAusgabe[$kapa["Stadtteil"]][] = 0;
          }
          else{
            $auslastung = $propChildren*$kinder["SummeKinder"]/$kapa["Kapa"];
            $prognoseAusgabe[$kapa["Stadtteil"]][] = round($auslastung, 2);
          }
        }
      }
    }

    // Prognose +4 Jahr
    foreach($ar_kapa as $kapa){
      foreach($ar_0bis3 as $kinder){
        if($kapa["Stadtteil"] == $kinder["Stadtteil"]){
          if($kapa["Kapa"] == 0){
            $prognoseAusgabe[$kapa["Stadtteil"]][] = 0;
          }
          else{
            $auslastung = $propChildren*$kinder["SummeKinder"]/$kapa["Kapa"];
            $prognoseAusgabe[$kapa["Stadtteil"]][] = round($auslastung, 2);
          }
        }
      }
    }

    // Prognose +5 Jahr
    foreach($ar_kapa as $kapa){
      foreach($ar_m0bis2 as $kinder){
        if($kapa["Stadtteil"] == $kinder["Stadtteil"]){
          if($kapa["Kapa"] == 0){
            $prognoseAusgabe[$kapa["Stadtteil"]][] = 0;
          }
          else{
            $auslastung = $birthrate*$propChildren*$kinder["SummeKinder"]/$kapa["Kapa"];
            $prognoseAusgabe[$kapa["Stadtteil"]][] = round($auslastung, 2);
          }
        }
      }
    }

    // Architektur der Ausgabe
    // $prognoseAusgabe = array(
    //   "Stadtteil1" => array(84.99,70,80,22,22),
    //   "Stadtteil2" => array(85,12,14),
    //   "Stadtteil3" => array(105,100,140),
    //   "Stadtteil4" => array(115,70,80),
    //   "Stadtteil5" => array(115.01,70,80),
    // );

    // Rückgabe der Egebnisse der Prognose
    return $prognoseAusgabe;
  }

  // Methode für das Beende der Verbindung zur Datenbank
  public function closeConnection(){
    $GLOBALS['conn']->close();
  }
}


?>
