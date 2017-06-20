<?php
/*
Diese Klasse ist für die Berechnung des Algorithmus zuständig.
@author Carsten Schober
*/

require 'Datenbankabfrage.php';

class Algorithmus
{

  private $cl_DatenBankabfrage;
  private $kapa;

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
    $sql_kapa = $cl_DatenBankabfrage->getKapazitaetProStadtteil("Buer");
    if ($row = $sql_kapa->fetch_assoc() == NULL){
      $kapa = 0;
    }
    else {
      $kapa = $row['Kapa'];
    }
    echo "Kapazität: " . $kapa;


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
