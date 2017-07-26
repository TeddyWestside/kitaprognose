<?php
/*
Diese Klasse ist für die Berechnung des Algorithmus zuständig.
@author Carsten Schober
*/

class Algorithmus
{
  public function getPrognose($propChildren,$birthrate){

    // Fehlerhandling, wenn keine Connection zur Datenbank vorhanden ist.
    if ($GLOBALS['conn'] == NULL){
        return;
    }

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
        throw new NoDatabaseException($GLOBALS['lang']->Error->NoDatabaseException);
      }

      // Fehlerhandling für einen leeren Datensatz.
      if($sql_kapa->num_rows == 0 | $sql_3bis6->num_rows == 0 | $sql_2bis5->num_rows == 0 | $sql_1bis4->num_rows == 0 | $sql_0bis3->num_rows == 0){
        throw new NoDataException($GLOBALS['lang']->Error->NoDataException);
      }
    }
    // Fangen der obingen beiden Exceptions und Ausgabe der Fehlers.
    catch (Exception $e){
      echo $e->getMessage();
      return;
    }

    // Füllen der Hilfsarrys mit den Werten der Mysql-Objekte.
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
            $auslastung = ($birthrate/100+1)*$propChildren*$kinder["SummeKinder"]/$kapa["Kapa"];
            $prognoseAusgabe[$kapa["Stadtteil"]][] = round($auslastung, 2);
          }
        }
      }
    }

    // Rückgabe der Egebnisse der Prognose
    return $prognoseAusgabe;
  }

  // Funktion, die die 5 Jahre zurückliefert, für die die Prognose durchgeführt wird
  public function getPrognosejahre(){
    $cl_DatenBankabfrage = new Datenbankabfrage();
    $jahr =  substr($cl_DatenBankabfrage->getNeusterDatensatzAlterStadtteil(), 0, 4);
    $array = array($jahr+1, $jahr+2, $jahr+3, $jahr+4, $jahr+5);
    return $array;
  }
}

?>
