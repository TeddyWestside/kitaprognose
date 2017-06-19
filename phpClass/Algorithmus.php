<?php
/*
Diese Klasse ist für die Berechnung des Algorithmus zuständig.
@author Carsten Schober
*/

require 'Datenbankabfrage.php';

class Algorithmus
{

  private $cl_DatenBankabfrage;
  private $Kitas;
  private $KapaKita;

  public function getPrognose($propChildren,$birthrate){

    // $test = $this->cl_DatenBankabfrage->getAnzahlKinder3bis6();
    // $kinder0b3 = $this->cl_DatenBankabfrage->getAnzahlKinder3bis6();
    // var_dump($kinder0b3);

    // Durchführung des Algorithmus
    // $prognoseAusgabe2;
    // while ($row = $kinder0b3->fetch_assoc()) {
    //   echo $row['Stadtteil_Bez'];
    //   echo $row['SummeKinder'];
    // }

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
    $cl_DatenBankabfrage = new Datenbankabfrage();
    $Kitas = $cl_DatenBankabfrage->getKitasInStadtteil("Beckhausen");
    $KapaKita = $cl_DatenBankabfrage->getKapazitaetProKita("Agnesstraße");

  }


}


?>
