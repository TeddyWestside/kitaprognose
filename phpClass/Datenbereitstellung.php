<?php
/**
* Kümmert sich um die gesamte Bereitstellung der Daten von der "opendata.
* gelsenkirchen.de" Seite.
*
* @author René Kanzenbach
*/
class Datenbereitstellung {

  //KONSTANTEN
  //----------------------------------------------------------------------------
  //Link zum Aufruf der OpenData-API
  const CO_OPENDATA_LINK = 'https://opendata.gelsenkirchen.de/api/action/datastore/search.json?';

  //Resource-ID's
  const CO_RESOURCE_KITA      = '5a27334a-4765-4535-8943-02ef8494f21b';
  const CO_RESOURCE_STADTTEIL = 'e5c405d5-3549-433a-ae1c-e81f6c3cc044';

  //Datenbankverbindung
  const CO_SERVERNAME = "localhost";
  const CO_USERNAME   = "root";
  const CO_PASSWORD   = "";

  //KLASSENATTRIBUTE
  //----------------------------------------------------------------------------
  //Datenbankverbindung
  private $gr_conn = null;

  //FUNKTIONEN
  //----------------------------------------------------------------------------

  /**
   * __CONSTRUCT
   *
   * @author René Kanzenbach
   */
  // public function __construct($ir_connection) {
  //   self::$gr_conn = $GLOBALS['conn'];
  // }
  public function __construct() {

    /*--DATENBANKVERBINDUNG AUFBAUEN--*/
    //Baue Datenbankverbindung auf
    $this->gr_conn = new mysqli(self::CO_SERVERNAME, self::CO_USERNAME, self::CO_PASSWORD);
    //Connection prüfen
    if ($this->gr_conn->connect_error) {
      //->Verbindung konnte nicht aufgebaut werden
      die("<br> Verbindung fehlgeschlagen: " . $lr_conn->connect_error);
    }
    //Charset auf UTF-8 setzen
    $this->gr_conn->set_charset('utf-8');
  }

  /**
  * AKTUALISIERE_DATENBESTAND
  * Aktualisiert sämtliche Datenbestände.
  *
  * @author René Kanzenbach
  */
  public function aktualisiere_datenbestand() {
    //JSON-Result Objekte
    $lr_kita_result;
    $lr_stadtteil_result;

    //=>Kitas
    $lr_kita_result = $this->lade_datensatz(self::CO_OPENDATA_LINK . 'resource_id='
      . self::CO_RESOURCE_KITA);
    $this->speicher_kitas($lr_kita_result);

    //=>AlterStadtteil
    $lr_stadtteil_result = $this->lade_datensatz(self::CO_OPENDATA_LINK
      . 'resource_id=' . self::CO_RESOURCE_STADTTEIL);
    $this->speicher_stadtteil($lr_stadtteil_result);
  }

  /**
  * LADE_DATENSATZ
  * Fragt einen neuen Kita-Datensatz über die OpenData-API ab.
  *
  * @param $iv_link: Link zum laden des Datensatzes
  * @return $lr_obj: Result Datensatz, welcher aus der JSON-Datei des Open-
  *  data Portals generiert wurde.
  * @author René Kanzenbach
  */
  private function lade_datensatz($iv_link) {

    $lv_json; //JSON-String
    $lr_obj;  //Aus dem JSON-String generiertes Result-Objekt

    //Datensatz als JSON-String anfordern
    $lv_json = file_get_contents($iv_link);

    //DEBUG
    // var_dump($lv_json);

    //Anpassen der Attributnamen innerhalb des JSON-Strings, damit diese im nächsten
    //Schritt erfolgreich in ein Objekt gewandelt werden können
    $lv_json = str_replace("0_bis_unter_1_jahr__m", "_0bis1m", $lv_json);
    $lv_json = str_replace("0_bis_unter_1_jahr__w", "_0bis1w", $lv_json);
    $lv_json = str_replace("1_bis_unter_2_jahre__m", "_1bis2m", $lv_json);
    $lv_json = str_replace("1_bis_unter_2_jahre__w", "_1bis2w", $lv_json);
    $lv_json = str_replace("2_bis_unter_3_jahre__m", "_2bis3m", $lv_json);
    $lv_json = str_replace("2_bis_unter_3_jahre__w", "_2bis3w", $lv_json);
    $lv_json = str_replace("3_bis_unter_4_jahre__m", "_3bis4m", $lv_json);
    $lv_json = str_replace("3_bis_unter_4_jahre__w", "_3bis4w", $lv_json);
    $lv_json = str_replace("4_bis_unter_5_jahre__m", "_4bis5m", $lv_json);
    $lv_json = str_replace("4_bis_unter_5_jahre__w", "_4bis5w", $lv_json);
    $lv_json = str_replace("5_bis_unter_6_jahre__m", "_5bis6m", $lv_json);
    $lv_json = str_replace("5_bis_unter_6_jahre__w", "_5bis6w", $lv_json);
    $lv_json = str_replace("6_bis_unter_7_jahre__m", "_6bis7m", $lv_json);
    $lv_json = str_replace("6_bis_unter_7_jahre__w", "_6bis7w", $lv_json);
    $lv_json = str_replace("7_bis_unter_8_jahre__m", "_7bis8m", $lv_json);
    $lv_json = str_replace("7_bis_unter_8_jahre__w", "_7bis8w", $lv_json);
    $lv_json = str_replace("8_bis_unter_9_jahre__m", "_8bis9m", $lv_json);
    $lv_json = str_replace("8_bis_unter_9_jahre__w", "_8bis9w", $lv_json);
    $lv_json = str_replace("9_bis_unter_10_jahre__m", "_9bis10m", $lv_json);
    $lv_json = str_replace("9_bis_unter_10_jahre__w", "_9bis10w", $lv_json);

    //DEBUG
    // var_dump($lv_json);

    //JSON-String in Objekt wandeln
    $lr_obj = json_decode($lv_json);

    //DEBUG
    // var_dump($lr_obj);

    //=>Rückgabe der Datensätze
    return $lr_obj->result;
  }

  /**
  * SPEICHER_KITAS
  * Leert die DB-Tabelle "Kitas" und fügt den übergebenen Datenbestand in
  * die Tabelle ein.
  *
  * @param $ir_kita_result: Result-Objekt der JSON-Kitadatensätze
  * @author René Kanzenbach
  */
  private function speicher_kitas($ir_kita_result) {

    /*--DEKLARATION--*/
    $lv_sql_delete;   //SQL-String zum leeren der Kita-Tabelle
    $lr_sql_stmt;     //Prepared SQL Statement

    //Parameter für das Prepared SQL Statement
    $id; $name; $art; $traeger; $plz; $ort; $strasse; $bezirk; $stadtteil; $x;
    $y; $telefon; $fax; $email; $internet; $info; $internetbeschreibung;
    $barrierefrei_inklusion; $anzahl_der_plaetze; $anzahl_der_gruppen; $betriebsnummer;

    /*--KITA-TABELLE LEEREN--*/
    $lv_sql_delete = 'DELETE FROM kitaprognose.kitas';
    $this->gr_conn->query($lv_sql_delete);

    /*--KITA-TABELLE MIT NEUEN DATEN BEFÜLLEN--*/
    //Statement vorbereiten
    $lr_sql_stmt = $this->gr_conn->prepare(
      "INSERT INTO kitaprognose.kitas (ID, NAME, ART, TRAEGER, PLZ, ORT, STRASSE,
        BEZIRK, STADTTEIL, X, Y, TELEFON, FAX, EMAIL, INTERNET, INFO,
        INTERNETBESCHREIBUNG, BARRIEREFREI_INKLUSION, ANZAHL_DER_PLAETZE,
        ANZAHL_DER_GRUPPEN, BETRIEBSNUMMER)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $lr_sql_stmt->bind_param('issssssssiisssssssiis', $id, $name, $art, $traeger,
    $plz, $ort, $strasse, $bezirk, $stadtteil, $x, $y, $telefon, $fax, $email,
    $internet, $info, $internetbeschreibung, $barrierefrei_inklusion,
    $anzahl_der_plaetze, $anzahl_der_gruppen, $betriebsnummer);

    //Statement füllen
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
      $email = $record->E_Mail;
      $internet = $record->Internet;
      // $info = $record->Info;
      $internetbeschreibung = $record->Internet;
      $barrierefrei_inklusion = $record->Barrierefrei;
      $anzahl_der_plaetze = $record->Anzahl_der_Plaetze;
      $anzahl_der_gruppen = $record->Anzahl_der_Gruppen;
      $betriebsnummer = $record->Betriebsnummer;

      //Statement ausführen
      if($lr_sql_stmt->execute()) {
        // echo "Datensatz eingefügt! id=" . $record->Id;
      }
    }
  }

  /**
  * SPEICHER_STADTTEIL
  * Leert die DB-Tabelle 'alterstadtteil' und füllt sie erneut mit dem Inhalt
  * des übergebenen Result-Objektes.
  *
  * @param $ir_stadtteil_result: Result-Objekt der JSON-AlterStadtteil Datensätze
  * @author René Kanzenbach
  */
  private function speicher_stadtteil($ir_stadtteil_result) {

    /*--DEKLARATION--*/
    $lv_sql_delete;   //SQL-String zum leeren der Kita-Tabelle
    $lr_sql_stmt;     //Prepared SQL Statement

    //Statement-Parameter
    $stichtag; $bezirk_id; $bezirk_bez; $stadtteil_id; $stadtteil_bez; $_0bis1m;
    $_0bis1w; $_1bis2m; $_1bis2w; $_2bis3m; $_2bis3w; $_3bis4m; $_3bis4w; $_5bis6m;
    $_5bis6w; $_6bis7m; $_6bis7w; $_7bis8m; $_7bis8w; $_8bis9m; $_8bis9w; $_9bis10m;
    $_9bis10w; $gesamtstadt;

    /*--TABELLE LEEREN--*/
    $lv_sql_delete = "DELETE FROM kitaprognose.alterstadtteil";
    $this->gr_conn->query($lv_sql_delete);

    /*--TABELLE BEFÜLLEN--*/
    //Statement vorbereiten
    $lr_sql_stmt = $this->gr_conn->prepare(
      "INSERT INTO kitaprognose.alterstadtteil (STICHTAG, BEZIRK_ID, BEZIRK_BEZ,
        STADTTEIL_ID, STADTTEIL_BEZ, 0BIS1M, 0BIS1W, 1BIS2M, 1BIS2W, 2BIS3M,
        2BIS3W, 3BIS4M, 3BIS4W, 4BIS5M, 4BIS5W, 5BIS6M, 5BIS6W, 6BIS7M, 6BIS7W,
        7BIS8M, 7BIS8W, 8BIS9M, 8BIS9W, 9BIS10M, 9BIS10W, GESAMTSTADT)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
          ?, ?, ?, ?)");

    //Statement befüllen
    $lr_sql_stmt->bind_param('sisisiiiiiiiiiiiiiiiiiiiii', $stichtag, $bezirk_id,
    $bezirk_bez, $stadtteil_id, $stadtteil_bez, $_0bis1m, $_0bis1w, $_1bis2m,
    $_1bis2w, $_2bis3m, $_2bis3w, $_3bis4m, $_3bis4w, $_4bis5m, $_4bis5w,
    $_5bis6m, $_5bis6w, $_6bis7m, $_6bis7w, $_7bis8m, $_7bis8w, $_8bis9m,
    $_8bis9w, $_9bis10m,$_9bis10w, $gesamtstadt);

    //Daten einfügen
    foreach ($ir_stadtteil_result->records as $record) {

      //DEBUG
      // var_dump($record);

      $stichtag = $this->formatiere_datum($record->stichtag);
      $bezirk_id = $record->bezirk_id;
      $bezirk_bez = $record->bezirk_bez;
      $stadtteil_id = $record->stadtteil_id;
      $stadtteil_bez = $record->stadtteil_bez;
      $_0bis1m = $record->_0bis1m;
      $_0bis1w = $record->_0bis1w;
      $_1bis2m = $record->_1bis2m;
      $_1bis2w = $record->_1bis2w;
      $_2bis3m = $record->_2bis3m;
      $_2bis3w = $record->_2bis3w;
      $_3bis4m = $record->_3bis4m;
      $_3bis4w = $record->_3bis4w;
      $_5bis6m = $record->_5bis6m;
      $_5bis6w = $record->_5bis6w;
      $_6bis7m = $record->_6bis7m;
      $_6bis7w = $record->_6bis7w;
      $_7bis8m = $record->_7bis8m;
      $_7bis8w = $record->_7bis8w;
      $_8bis9m = $record->_8bis9m;
      $_8bis9w = $record->_8bis9w;
      $_9bis10m = $record->_9bis10m;
      $_9bis10w = $record->_9bis10w;
      $gesamtstadt = $record->gesamtstadt;

      //Statement ausführen
      if($lr_sql_stmt->execute()) {
        // echo "Datensatz eingefügt! Stichtag=" . $record->stichtag;
      }
    }
  }

  /**
   * FORMATIERE_DATUM
   * Bringt das Datum vom OpenData-Format "TT.MM.JJJJ" in das SQL-Format
   * "JJJJ-MM-TT".
   *
   * @param $iv_datum: Datum im Format "TT.MM.JJJJ"
   * @return $rv_datum: Datum im Format "JJJJ-MM-TT"
   * @author René Kanzenbach
   */
  private function formatiere_datum($iv_datum) {

    $rv_datum;
    $lv_tag = substr($iv_datum, 0, 2);
    $lv_monat = substr($iv_datum, 3, 2);
    $lv_jahr = substr($iv_datum, 6, 4);
    $rv_datum = $lv_jahr . '-' . $lv_monat . '-' . $lv_tag;
    return $rv_datum;
  }

 }

?>
