<?php
/**
 * Kümmert sich um die gesamte Bereitstellung der Daten von der "opendata.
 * gelsenkirchen.de" Seite.
 */
class Datenbereitstellung {

  //KONSTANTEN
  //----------------------------------------------------------------------------
  const CO_OPENDATA_LINK = 'https://opendata.gelsenkirchen.de/api/action/datastore/search.json?';

  //Resource-ID's
  const CO_RESOURCE_KITA = '5a27334a-4765-4535-8943-02ef8494f21b';

  //FUNKTIONEN
  //----------------------------------------------------------------------------

  /**
   * Testfunktion
   */
   public function test() {

     $json = file_get_contents('https://opendata.gelsenkirchen.de/api/action/datastore/search.json?resource_id=81c5bb92-b75f-41bb-b544-e12fe158fcdf&fields=id,name&limit=5');
     $obj = json_decode($json);
     echo $obj->result->records[0]->id;
    //  var_dump($obj);
   }

   /**
    *
    */
   public function lade_kitas() {

     //Link zum laden des Kita-Datensatzes zusammen bauen
     $lv_link = self::CO_OPENDATA_LINK . 'resource_id=' . self::CO_RESOURCE_KITA;

     echo $lv_link . '<br>';

     //JSON Datei anfordern und in Objekt wandeln
     $lv_json = file_get_contents($lv_link);
     $lr_obj = json_decode($lv_json);

     //Testausgabe
     echo $lr_obj->result->total;

   }

 }

?>
