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

  //Datenbankverbindung
  const CO_SERVERNAME = "localhost";
  const CO_USERNAME   = "root";
  const CO_PASSWORD   = "";

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
  * Aktualisiert sämtliche Datenbestände.
  */
  public function aktualisiere_datenbestand() {

    //=>Kitas
    $lv_kita_result = self::lade_kitas();
    self::speicher_kitas($lv_kita_result);

    //=>AlterStadtteil
    //ToDo
  }

  /**
  * Fragt einen neuen Kita-Datensatz über die OpenData-API ab.
  */
  private function lade_kitas() {

    //Link zum laden des Kita-Datensatzes zusammen bauen
    $lv_link = self::CO_OPENDATA_LINK . 'resource_id=' . self::CO_RESOURCE_KITA;

    //JSON Datei anfordern und in Objekt wandeln
    $lv_json = file_get_contents($lv_link);
    $lr_obj = json_decode($lv_json);

    //Rückgabe der Datensätze
    return $lr_obj->result;
  }

  /**
  * Leert die DB-Tabelle "Kitas" und fügt den übergebenen Datenbestand in
  * die Tabelle ein.
  */
  private function speicher_kitas($ir_kita_result) {

    //Baue Datenbankverbindung auf
    $lr_conn = new mysqli(self::CO_SERVERNAME, self::CO_USERNAME, self::CO_PASSWORD);
    // Connection prüfen
    if ($lr_conn->connect_error) {
      die("<br> Verbindung fehlgeschlagen: " . $lr_conn->connect_error);
    }

    //Löschen des bisherigen Datenbestands
    $lv_sql_delete = 'DELETE FROM kitaprognose.kitas';
    $lr_conn->query($lv_sql_delete);

    //Einfügen des neuen Datenbestands
    //Statement vorbereiten
    $lr_sql_stmt = $lr_conn->prepare(
      "INSERT INTO kitaprognose.kitas (ID, NAME, ART, TRAEGER, PLZ, ORT, STRASSE,
        BEZIRK, STADTTEIL, X, Y, TELEFON, FAX, EMAIL, INTERNET, INFO,
        INTERNETBESCHREIBUNG, BARRIEREFREI_INKLUSION, ANZAHL_DER_PLAETZE,
        ANZAHL_DER_GRUPPEN, BETRIEBSNUMMER)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $lr_sql_stmt->bind_param('issssssssiisssssssiis', $id, $name, $art, $traeger,
      $plz, $ort, $strasse, $bezirk, $stadtteil, $x, $y, $telefon, $fax, $email,
      $internet, $info, $internetbeschreibung, $barrierefrei_inklusion,
      $anzahl_der_plaetze, $anzahl_der_gruppen, $betriebsnummer);

      // var_dump($ir_kita_result->records);

    //Daten einfügen
    foreach ($ir_kita_result->records as $record) {
      $id = $record->Id;
      $name = $record->Name;
      $art = $record->Art;
      $traeger = $record->Traeger;
      $plz = $record->PLZ;
      $ort = $record->Ort;
      $strasse = $record->Strasse;
      $bezirk = $record->Bezirk;
      $stadtteil = $record->Stadtteil;
      $x = $record->X;
      $y = $record->Y;
      $telefon = $record->Telefon;
      $fax = $record->Fax;
      // $email = $record->E-Mail;
      $internet = $record->Internet;
      // $info = $record->Info;
      // $internetbeschreibung = $record->Internetbeschreibung;
      // $barrierefrei_inklusion = $record->Barrierefrei (Inklusion);
      // $anzahl_der_plaetze = $record->Anzahl_der_Plaetze;
      // $anzahl_der_gruppen = $record->
      // $betriebsnummer = $record->


      //Statement ausführen
      if($lr_sql_stmt->execute()) {
        echo 'Datensatz eingefügt! id=' . $record->Id;
      }
    }



    //Datenbankverbindung schließen
    $lr_conn->close();
  }

 }

?>
