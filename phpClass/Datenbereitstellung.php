<?php

/**
* Kümmert sich um die gesamte Bereitstellung der Daten von der "opendata.
* gelsenkirchen.de" Seite.
*
* @author René Kanzenbach
*/
class Datenbereitstellung {

  //KLASSENATTRIBUTE
  //----------------------------------------------------------------------------
  //Array mit Inhalt der Konfigurationsdatei
  private $ga_config = array();
  //Klasse mit sprachunabhängigen Texten
  private $gr_sprache = null;
  //Datenbankverbindung
  private $gr_conn = null;
  //Kennzeichen, dass eine lokale Datenbankverbindung erzeugt wurde
  private $gv_lokale_verbindung = 0;
  //Pfad zur Datei mit den manuell gepflegten Kitakapazitäten
  private $gv_pfad_manuelle_kap = "";

  //FUNKTIONEN
  //----------------------------------------------------------------------------

  /**
   * __CONSTRUCT
   * Liest die global verfügbare Datenbankverbindung, Sprachtexte und allgemeine
   * Konfiguration aus.
   * Konnte keine Datenbankverbindung ausgelesen werden, wird eine lokale Ver-
   * bindung aufgebaut.
   *
   * @param $ir_sprache: Objekt welches die Texte in der aktuell verwendeten
   *  Sprache enthält
   * @throws NoDatabaseException
   * @author René Kanzenbach
   */
  public function __construct() {

    //Sprache setzen
    $this->gr_sprache = $GLOBALS["lang"];
    //Konfigurationsdatei laden
    $this->ga_config = $GLOBALS["config"];

    //Datenbankverbindung aufbauen
    //--------------------------------------------------------------------------
    //Datenbankverbindung auslesen
    $this->gr_conn = $GLOBALS["conn"];
    //Datenbankverbindung prüfen
    if ($this->gr_conn->connect_error) {
      //->Verbindung konnte nicht ausgelesen werden

      //Eigene DB-Verbindung erzeugen
      $this->gr_conn = new mysqli($this->ga_config["servername"], $this->ga_config["username"],
        $this->ga_config["password"]);
      //Charset auf UTF-8 setzen
      $this->gr_conn->set_charset('utf8');
      if ($this->gr_conn->connect_error) {
        //->Verbindung konnte nicht aufgebaut werden
        throw NoDatabaseException($gr_sprache->Error->NoDBConnection);
      }
      $this->gv_lokale_verbindung = 1;
    }
  }

  /**
   * __DESTRUCT
   * Prüft, ob eine lokale Datenbankverbindung erzeugt wurde. Falls ja wird
   * diese wieder geschlossen.
   *
   * @author René Kanzenbach
   */
  public function __destruct() {

    if ($this->gv_lokale_verbindung == 1) {
      //->Es wird eine lokale Datenbankverbindung genutzt
      //Verbindung schließen
      $this->gr_conn->close();
      $this->gv_lokale_verbindung = 0;
    }
  }

  /**
   * INITIALISIERE_DATENBESTAND
   * Leert den gesamten Datenbestand und füllt ihn anschließend neu mit den
   * Daten der OpenData-API.
   *
   * @author René Kanzenbach
   */
  public function initialisiere_datenbestand() {

    //Result-Arrays
    $la_kita_result;
    $la_stadtteil_result;

    //=>Verbindung zur OpenData-API prüfen
    //--------------------------------------------------------------------------
    $this->pruefe_verbindung();

    //=>Kita-Datenbestand aktuallisieren
    //--------------------------------------------------------------------------
    //Kita Datensätze laden
    $la_kita_result = $this->lade_datensatz($this->ga_config["opendata_link"].'resource_id='
      .$this->ga_config["resource_kita"]);
    //Datensätze in DB speichern
    $this->speicher_kitas($la_kita_result);

    //Letztes Update-Datum der Kita-Ressource ermitteln
    $lv_update_datum = $this->get_update_datum($this->ga_config["resource_kita"]);
    //Letztes Update-Datum der Ressource speichern
    $this->set_update_datum(0, $lv_update_datum);

    //=>AlterStadtteil-Datenbestand aktualisieren
    //--------------------------------------------------------------------------
    //Stadteil Datensätze laden
    $la_stadtteil_result = $this->lade_datensatz($this->ga_config["opendata_link"]
      . 'resource_id=' . $this->ga_config["resource_stadtteil"]);
    //Datensätze in DB speichern
    $this->speicher_stadtteil($la_stadtteil_result);

    //Letzes Update-Datum der Stadteil-Ressource ermitteln
    $lv_update_datum = $this->get_update_datum($this->ga_config["resource_stadtteil"]);
    //Letztes Update-Datum der Ressource speichern
    $this->set_update_datum(1, $lv_update_datum);

    //=>Fehlende Kapazitätsplätze manuell eintragen
    //--------------------------------------------------------------------------
    $this->fuelle_leere_kitaplaetze();
  }

  /**
  * AKTUALISIERE_DATENBESTAND
  * Prüft anhand des Änderungsdatums der Datenbestände der OpenData-API ob sich
  * die Daten seit dem letzten Laden geändert haben. Ist dies der Fall, werden
  * die lokalen Datenbestände aktualisiert.
  *
  * @author René Kanzenbach
  */
  public function aktualisiere_datenbestand() {
    //Result-Arrays
    $la_kita_result;
    $la_stadtteil_result;

    //Aktuelles Updatedatum der OpenData-API einer Ressource
    $lv_update_datum;     //Aktuell

    //Updatedatum der Ressourcen der OpenData-API, al die Ressourcen das Letzte
    //mal mit dem lokalen Datenbestand abgeglichen wurden
    $lv_update_kita = ""; //Letztes Update der Kitas
    $lv_update_stadtteil = ""; //Letztes Update der Staddteile

    //=>Verbindung zur OpenData-API prüfen
    //--------------------------------------------------------------------------
    $this->pruefe_verbindung();

    //=>Kita-Datenbestand aktuallisieren
    //--------------------------------------------------------------------------
    //Letztes Update-Datum der Kita-Ressource ermitteln
    $lv_update_datum = $this->get_update_datum($this->ga_config["resource_kita"]);

    if ($lv_update_kita == "" && $lv_update_kita <> $lv_update_datum) {
      //->Kitas wurden noch nie geladen oder die Ressource auf der OpenData-Seite
      //wurde seit dem letzten Laden aktualisiert

      //Kita Datensätze von OpenData-API laden
      $la_kita_result = $this->lade_datensatz($this->ga_config["opendata_link"] . 'resource_id='
        . $this->ga_config["resource_kita"]);
      //Datensätze in DB speichern
      $this->speicher_kitas($la_kita_result);
      //Letztes Update-Datum der Ressource speichern
      $this->set_update_datum(0, $lv_update_datum);
    }

    //=>AlterStadtteil-Datenbestand aktualisieren
    //--------------------------------------------------------------------------
    //Letzes Update-Datum der Stadteil-Ressource ermitteln
    $lv_update_datum = $this->get_update_datum($this->ga_config["resource_stadtteil"]);

    if ($lv_update_stadtteil == "" && $lv_update_stadtteil <> $lv_update_datum) {
      //->Stadteile wurden noch nie geladen oder die Ressource auf der OpenData-
      //Seite wurde seit dem letzten Laden aktualisiert

      //Stadteil Datensätze von OpenData-API laden
      $la_stadtteil_result = $this->lade_datensatz($this->ga_config["opendata_link"]
        . 'resource_id=' . $this->ga_config["resource_stadtteil"]);
      //Datensätze in DB speichern
      $this->speicher_stadtteil($la_stadtteil_result);
      //Letztes Update-Datum der Ressource speichern
      $this->set_update_datum(1, $lv_update_datum);
    }

    //=>Fehlende Kapazitätsplätze manuell eintragen
    //--------------------------------------------------------------------------
    $this->fuelle_leere_kitaplaetze();
  }

  /**
  * LADE_DATENSATZ
  * Fragt einen neuen Kita-Datensatz über die OpenData-API ab.
  *
  * @param $iv_link: Link zum laden des Datensatzes
  * @return $ra_result: Result Datensatz, welcher aus der JSON-Datei des Open-
  *  data Portals generiert wurde.
  * @author René Kanzenbach
  */
  private function lade_datensatz($iv_link) {

    $lv_json;             //JSON-String
    $lr_obj;              //Aus dem JSON-String generiertes Result-Objekt
    $lv_offset = 0;       //Offset-Parameter für OpenData-Portal
    $lv_dyn_link = "";    //Link mit dynamischen Offset
    $ra_result = array(); //Return Array mit abgefragten Datensätzen

    $lv_dyn_link = $iv_link."&offset=";

    //Über das OpenData-Portal können pro Aufruf nur max 100 Datensätze abgefragt
    //werden. Deshalb müssen mehrere Abfragen versendet werden, welche durch
    //Verwendung des OFFSET-Parameters bei anderen Datensätzen anfangen.
    do {

      //Link mit dynamischen Offset zusammensetzen
      $lv_dyn_link = "";
      $lv_dyn_link = $iv_link."&offset=".$lv_offset;

      //Offset erhöhen
      $lv_offset = $lv_offset + 100;
      //Datensatz als JSON-String anfordern
      $lv_json = file_get_contents($lv_dyn_link);

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

      //JSON-String in Objekt wandeln
      $lr_obj = json_decode($lv_json);
      //Neue Datensätze an das Return-Array hängen
      $ra_result = array_merge($ra_result, $lr_obj->result->records);

      //->Wiederhole bis keine Datensätze mehr zurückgegeben werden
    } while (sizeof($lr_obj->result->records) != 0);

    return $ra_result;
  }

  /**
  * SPEICHER_KITAS
  * Leert die DB-Tabelle "Kitas" und fügt den übergebenen Datenbestand in
  * die Tabelle ein.
  *
  * @throws NoDatabaseException: Keine Verbindung zur Datenbank
  * @param $ia_kita_result: Array mit Kita-Datensatz
  * @author René Kanzenbach
  */
  private function speicher_kitas($ia_kita_result) {

    $lv_sql_delete;   //SQL-String zum leeren der Kita-Tabelle
    $lr_sql_stmt;     //Prepared SQL Statement
    $lv_i = 0;

    //Parameter für das Prepared SQL Statement
    $id; $name; $art; $traeger; $plz; $ort; $strasse; $bezirk; $stadtteil; $x;
    $y; $telefon; $fax; $email; $internet; $info; $internetbeschreibung;
    $barrierefrei_inklusion; $anzahl_der_plaetze; $anzahl_der_gruppen; $betriebsnummer;

    //=>Prüfen ob die Datenbank erreichbar ist
    //--------------------------------------------------------------------------
    if($this->gr_conn->connect_error){
      //->Fehler mit der Datenbankverbindung
      throw new NoDatabaseException();
    }

    //=>Kita Tabelle leeren
    //--------------------------------------------------------------------------
    $lv_sql_delete = 'DELETE FROM kitaprognose.kitas';
    $this->gr_conn->query($lv_sql_delete);

    //=>Kita Tabelle mit neuen Daten befüllen
    //--------------------------------------------------------------------------
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
    foreach ($ia_kita_result as $record) {

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
      $internetbeschreibung = $record->Internet;
      $barrierefrei_inklusion = $record->Barrierefrei;
      $anzahl_der_plaetze = $record->Anzahl_der_Plaetze;
      $anzahl_der_gruppen = $record->Anzahl_der_Gruppen;
      $betriebsnummer = $record->Betriebsnummer;

      //Statement ausführen
      if(!$lr_sql_stmt->execute()) {
        //->Statement konnte nicht durchgeführt werden
        $this->gr_conn->rollback();
        throw new NoDatabaseException($this->gr_sprache->DBUpdateError);
      }
    }
    //Update durchführen
    $this->gr_conn->commit();
  }

  /**
  * SPEICHER_STADTTEIL
  * Leert die DB-Tabelle 'alterstadtteil' und füllt sie erneut mit dem Inhalt
  * des übergebenen Result-Objektes.
  *
  * @param $ia_stadtteil_result: Result-Array der JSON-AlterStadtteil Datensätze
  * @throws NoDatabaseException
  * @author René Kanzenbach
  */
  private function speicher_stadtteil($ia_stadtteil_result) {

    $lv_sql_delete  = "";       //SQL-String zum leeren der Kita-Tabelle
    $lr_sql_stmt    = null;     //Prepared SQL Statement

    //Statement-Parameter
    $stichtag; $bezirk_id; $bezirk_bez; $stadtteil_id; $stadtteil_bez; $_0bis1m;
    $_0bis1w; $_1bis2m; $_1bis2w; $_2bis3m; $_2bis3w; $_3bis4m; $_3bis4w; $_5bis6m;
    $_5bis6w; $_6bis7m; $_6bis7w; $_7bis8m; $_7bis8w; $_8bis9m; $_8bis9w; $_9bis10m;
    $_9bis10w; $gesamtstadt;

    //=>Tabelle leeren
    //--------------------------------------------------------------------------
    $lv_sql_delete = "DELETE FROM kitaprognose.alterstadtteil";
    $this->gr_conn->query($lv_sql_delete);

    //=>Tabelle befüllen
    //--------------------------------------------------------------------------
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
    foreach ($ia_stadtteil_result as $record) {

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
      $_4bis5m = $record->_4bis5m;
      $_4bis5w = $record->_4bis5w;
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
      if(!$lr_sql_stmt->execute()) {
        //->Statement konnte nicht ausgeführt werden
        $this->gr_conn->rollback();
        throw new NoDatabaseException($this->gr_sprache->DBUpdateError);
      }
    }
    //Statements commiten
    $this->gr_conn->commit();
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


  /**
  * FUELLE_LEERE_KITAPLAETZE
  * Prüft ob Kindertagesstädten existieren, für die keine Kapazitätsplätze
  * gepflegt sind und füllt diese wenn möglich mit selbst ermittelten Daten.
  *
  * @author René Kanzenbach
  */
  private function fuelle_leere_kitaplaetze() {

    $lv_sql = "";           //SQL-Statement
    $lr_sql_prep = null;    //Prepared SQL-Statement
    $lr_kitas_leer;         //Kitas, für die keine Kapazitäten gepflegt sind
    $la_kita_leer;          //Array mit Kita ohne Kapazitäten
    $lr_datei = null;       //Ressource der Kapazitaeten.csv
    $la_csv_zeile ;         //Array mit dem Inhalt einer CSV-Zeile
    $lv_kita_id = 0;        //ID einer Kita
    $lv_kita_kap = 0;       //Kapazität einer Kita
    $la_man_kap = array();  //Array mit manuell gepflegten Kapazitäten

    //=>Auslesen aller Kitas, für die keine Kapazitätsplätze gepflegt wurden
    //--------------------------------------------------------------------------
    $lv_sql = "SELECT ID, ANZAHL_DER_PLAETZE
                FROM kitaprognose.kitas
                WHERE ANZAHL_DER_PLAETZE = 0";

    //Verbindung prüfen
    if ($this->gr_conn->connect_error) {
      //->Es besteht keine Datenbankverbindung
      die("Es konnte nicht auf die Datenbank zugegriffen werden!");
    }

    //Statement ausführen
    $lr_kitas_leer = $this->gr_conn->query($lv_sql);

    //=>Update-Statement vorbereiten
    //--------------------------------------------------------------------------
    $lr_sql_prep = $this->gr_conn->prepare(
      "UPDATE kitaprognose.kitas
        SET ANZAHL_DER_PLAETZE = ?
        WHERE ID = ?");

    //=>Auslesen der manuell gepflegten Kapazitätsplätze
    //--------------------------------------------------------------------------
    //Datei öffnen
    $lr_datei = fopen($this->gv_pfad_manuelle_kap, "r")
    or die("Datei mit den manuellen Kapaziätsplätzen konnte nicht geöffnet werden");

    //Erste Zeile der CSV-Datei einlesen (Die erste Zeile ist die Headerline
    //und wird nicht benötigt)
    $la_csv_zeile = fgetcsv($lr_datei,30,";");
    //Zweite Zeile der CSV-Datei einlesen
    $la_csv_zeile = fgetcsv($lr_datei,30,";");

    //Übertragen der einzelnen CSV-Zeilen in ein Array
    while(count($la_csv_zeile) <> 1){

      //Werte nach int casten
      $lv_kita_id = intval($la_csv_zeile[0]);
      $lv_kita_kap = intval($la_csv_zeile[1]);

      //Array-Zeile anfügen
      $la_man_kap[$lv_kita_id] = $lv_kita_kap;

      //Nächste Zeile der CSV-Datei auslesen
      $la_csv_zeile = fgetcsv($lr_datei,30,";");
    }

    //Update durchführen
    //--------------------------------------------------------------------------
    //Transaktion beginnen
    $this->gr_conn->begin_transaction();

    //Bearbeiten aller leeren Kitas
    while (($la_kita_leer = $lr_kitas_leer->fetch_assoc()) != null) {

      //Kita-ID nach int casten
      $lv_kita_id = intval($la_kita_leer["ID"]);
      $lv_kita_kap = intval($la_kita_leer["ANZAHL_DER_PLAETZE"]);

      //Prüfen, ob für diese Kita bereits Kapazitäten vorliegen
      if (array_key_exists($lv_kita_id, $la_man_kap)
          & $lv_kita_kap == 0) {
        //->Für diese leere Kita existiert eine manuell gepflegte Kapazität

        //SQL-Statement füllen
        $lr_sql_prep->bind_param("ii", $la_man_kap[$lv_kita_id], $lv_kita_id);
        //SQL-Statement ausführen
        $lr_sql_prep->execute();
      }
    }

    //Transaktion abschließen
    if (!$this->gr_conn->commit()) {
      //->Transaktion fehlgeschlagen
      $this->gr_conn->rollback();
    }
  }

  /**
   * PRUEFE_VERBINDUNG
   * Prüft ob das OpenData-Portal erreichbar ist oder nicht.
   *
   * @throws Exception
   * @author René Kanzenbach
   */
  private function pruefe_verbindung() {

    $lv_result_json = "";
    $lr_result_obj  = null;

    //Datensatz als JSON-String anfordern
    $lv_result_json = file_get_contents($this->ga_config["opendata_link_funk"]."site_read");
    //JSON-String in Objekt wandeln
    $lr_result_obj = json_decode($lv_result_json);

    //Prüfe ob API erreichbar ist
    if ($lr_result_obj->success != true) {
      //->API ist nicht erreichbar
      throw new NoConnectionException("Es kann keine Verbindung zur OpenData-API
        aufgebaut werden!");
    }
  }

  /**
   * GET_UPDATE_DATUM
   *
   * Gibt das Datum zurück, an dem die übergebene Ressource das letzte mal auf
   * der OpenData-Website geändert wurde. Dazu wird das Änderungsdatum der
   * Ressource über die DKAN-API mittels einer 'ressource_show'-Funktion abgefragt.
   *
   * @param $iv_resource_id Ressource-ID der Ressource, für die das Änderungsdatum
   *  ermittelt werden soll.
   * @return Datum an dem die Ressource das letzte mal geändert wurde.
   * @author René Kanzenbach
   */
  private function get_update_datum($iv_resource_id){

    $lv_result_json = "";
    $lr_result_obj = "";

    //Datensatz als JSON-String anfordern
    $lv_result_json = file_get_contents($this->ga_config["opendata_link_funk"]
      ."resource_show?id=".$iv_resource_id);
    //JSON-String in Objekt wandeln
    $lr_result_obj = json_decode($lv_result_json);

    //Rückgabe des letzten Änderungsdatums
    return substr($lr_result_obj->result->last_modified, 13);
  }

  /**
   * SET_FEHLENDE_KAPAZITAETEN
   * Setzt den Pfad für die CSV-Datei mit den manuell gepflegten Kitakapazitäten.
   *
   * @param $iv_pfad: Pfad der CSV-Datei
   * @author René Kanzenbach
   */
  public function set_fehlende_kapazitaeten($iv_pfad) {
    $this->gv_pfad_manuelle_kap = $iv_pfad;
  }

  /**
   * SET_UPDATE_DATUM
   *
   */
  public function set_update_datum($iv_schluessel, $iv_wert) {

    $lr_sql_prep = null;     //Prepared SQL-Statement

    //Update-Statement vorbereiten
    //--------------------------------------------------------------------------
    $lr_sql_prep = $this->gr_conn->prepare(
      "UPDATE kitaprognose.zwischenspeicher
        SET BESCHREIBUNG = ?, WERT = ?
        WHERE ID = ?");

    switch ($iv_schluessel) {
      case 0:
        $lv_beschreibung = "Kita Updatedatum";
      case 1:
        $lv_beschreibung = "Stadtteil Updatedatum";
    }

    $lr_sql_prep->bind_param("ssi", $lv_beschreibung, $iv_wert, $iv_schluessel);

    //Transaktion durchführen
    //--------------------------------------------------------------------------
    //Transaktion beginnen
    $this->gr_conn->begin_transaction();
    //Statement ausführen
    $lr_sql_prep->execute();

    //Transaktion abschließen
    if (!$this->gr_conn->commit()) {
      //->Transaktion fehlgeschlagen
      $this->gr_conn->rollback();
    }
  }
}
?>
