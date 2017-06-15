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
  private $AnzahlKinder3bis6;
  private $AnzahlKinder2bis5;
  private $AnzahlKinder1bis4;
  private $AnzahlKinder0bis3;

  public function getPrognose($propChildren,$birthrate){
    // Architektur der Ausgabe
    $prognoseAusgabe = array(
      "Stadtteil1" => array(60,70,80),
      "Stadtteil2" => array(10,12,14),
      "Stadtteil3" => array(100,120,140),
      "Stadtteil4" => array(60,70,80),
      "Stadtteil5" => array(60,70,80),
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
