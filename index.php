<!--
author: Johannes Kusber
description: Die index.php erstellt die GUI. Dazu gehören die Möglichtkeiten die
Prognose zu parametriesieren, Filter zu erstellen und im Ergebnis zu sortieren.
Die Datei ruft den Prognosealgorithmus und zeigt das Ergebnis an. Der Header und
der Footer werden jeweils extra eingebunden.
-->

<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

  <!-- Importieren der Material Icons von Google. Vorteil diese nicht lokal zur
  Verfügung zu stellen ist es das diese eventuell bereits im Cache des Browsers
  liegen. Des Weiteren Import des CSS-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

  <?php
  //Laden der Konfigurationsparameter, die zentral in der config.php festgelegt werden
  $GLOBALS["config"] = include "config.php";
  $config = $GLOBALS["config"];

  //Definierung der Standardsprache
  $langCode = $config["langCode"];
  //Definierung Standard % Anzahl Kinder im Kindergarten
  $propChildren = $config["propChildren"];
  //Definierung Standard % Geburtenrate
  $birthrate = $config["birthrate"];

  //Prüfen der Get-Paramenter in URL und falls vorhanden Anpassung der Werte
  if (isset($_GET["langCode"])) {
    $langCode = $_GET["langCode"];
  }
  if (isset($_GET["propChildren"]) && $_GET["propChildren"] != "") {
    $propChildren = $_GET["propChildren"];
  }
  if (isset($_GET["birthrate"]) && $_GET["birthrate"] != "") {
    $birthrate = $_GET["birthrate"];
  }

  //Einbindung der language.php Datei um Sprachunabhängigkeit in der GUI zu ermöglichen.
  require ('lang\Language.php');
  /*Instanzierung der language-Klasse und speichern der JSON-Variable in $lang um auf die Strings über
  die IDs zugreifen zu können.
  */
  $language = new language("lang/". $langCode);
  $GLOBALS["lang"] = $language->translate();
  $lang = $GLOBALS["lang"];
  ?>

  <!-- Erstellen des Seitentitels -->
  <title><?php echo $lang->Basic->program_Name?></title>
</head>
<body>

  <span id="error"></span>

  <!--Einbindung des Headers -->
  <?php include('header.php'); ?>
  <!-- Definition des Hauptbereichs der Seite -->
  <main>
    <div class="container">
      <!-- Erster Block Parameterkonfiguration -->
      <ul class="collapsible" data-collapsible="accordion">
        <li>
          <!-- Titel des Blocks definieren -->
          <div class="collapsible-header"><i class="material-icons">mode_edit</i><?php echo $lang->Main->title_parameterConfig ?></div>
          <div class="collapsible-body">
            <div class="row">
              <!-- Formular für die Parameterkonfiguration -->
              <form class="col l12" method="get">
                <div class="row">
                  <?php echo $lang->Main->text_parameterConfig?>
                </div>
                <!-- Erste Reihe mit Inputfeldern -->
                <div class="row">
                  <!-- Inputfeld für prozentualen Anteil der Kinder, die einen Kindergarten besuchen mit HTML 5 Formularvalidierung-->
                  <div class="input-field col l6">
                    <input placeholder=<?php echo $propChildren ?> id="propChildren"
                    name="propChildren" type="text"
                    pattern="100|100\.00|100\.0|\d{2}|\d{2}\.\d|\d{2}\.\d{2}|\d|\d\.\d|\d\.\d{2}"
                    class="validate" title="<?php echo $lang->Main->title_validation ?>">
                    <label for="propChildren"><?php echo $lang->Main->label_propChildren ?></label>
                  </div>
                  <!-- Inputfeld für Geburtenrate in Prozent mit HTML 5 Formularvalidierung -->
                  <div class="input-field col l6">
                    <input placeholder=<?php echo $birthrate ?> id="birthrate" name="birthrate" type="text"
                    pattern="\d{3}|\d{3}\.\d|\d{3}\.\d{2}|\d{2}|\d{2}\.\d|\d{2}\.\d{2}|\d|\d\.\d|\d\.\d{2}"
                    class="validate" title="<?php echo $lang->Main->title_validation ?>">
                    <label for="birthrate"><?php echo $lang->Main->label_birthrate ?></label>
                  </div>
                  <input type="hidden" name="lang" value=<?php echo $langCode ?>>
                </div>
                <!-- Zweite Reihe mit submitbutton -->
                <div class="row">
                  <button type="submit" class="waves-effect waves-light btn col l12"><?php echo $lang->Main->text_parameterButton ?></button>
                </div>
              </form>
            </div>
          </div>
        </li>
      </ul>
      <!-- Zweiter Block Filter und Vorhersagejahr -->
      <div class="row">
        <div class="col l12">
          <div class="card-panel">
            <!-- Definition des Filterbereichs (hidden wird am Ende nach Abschluss des Tabellenbaus sichtbar sobald ein Ergebnis vom Algorithmus vorhanden ist damit sie bei einem Fehler unsichtbar ist) -->
            <div class="row" id="filterRow" style="visibility:hidden">
              <!-- Definition des Multiselects zum Filtern der Stadtteile -->
              <div class="input-field col l6">
                <select id="filter" multiple onchange="buildTable();">
                </select>
                <label><?php echo $lang->Main->label_filter ?></label>

              </div>
              <!-- Definition des Sliders zum Einstellen des Vorhersagejahrs -->
              <div class="col l6">
                <label><?php echo $lang->Main->label_forecastPeriod ?><span id="forecastPeriodYear">X</span></span></label>
                <input type="range" id="forecastPeriod" onchange="buildTable()"  value="1" min="1">
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Dritter Block Tabelle -->
      <div class="row">
        <div class="col l12">
          <div class="card-panel">
            <!-- Bereitstellen des Bereichs der von der Methode buildTable() überschrieben wird -->
            <span id="tablePlaceholder">
            </span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!--Einbindung des Footers -->
  <?php include('footer.php');?>
</body>

<!-- PHP Bereich in dem der die Datenbereitstellung vom OpenData Protal und der
Algorithmus ausgeführt wird -->
<?php
//Verschieben der Errormeldungen nach oben
echo "<span style='position:absolute; top: 0; background: red'>";
//Einbindung & Aufbau der Verbindung zur Datenbank
include 'connection.php';
//Prüfung ob Verbindung vorhanden
if($GLOBALS['conn'] != null){
  //Aktualisieren des lokalen Datenbestandes falls neue Daten im OpenData Portal existieren und Laden fehlender Kapazitäten
  include "phpClass/Datenbereitstellung.php";
  $datenbereitstellung = new Datenbereitstellung();
  $datenbereitstellung->set_fehlende_kapazitaeten("files/Fehlende_Kapazitaeten.csv");
  $datenbereitstellung->aktualisiere_datenbestand();

  //Einbindung des Algorithmus
  require "phpClass/Algorithmus.php";
  //Erzeugen eines Algorithmus Objekt
  $algo = new Algorithmus;
  //Erzeugen eines Ergebnisses mit aktuellen Parametern
  $result = $algo->getPrognose($propChildren, $birthrate);
  //Abfrage des Vorhersagejahres
  $forecastPeriodYear = $algo->getPrognosejahre();
}
//Falls keine Verbindung vorhanden wird abgebrochen
else{
  die();
}
// Abschluss des Verschieben der Errormeldung
echo "</span>";
?>

<!-- Skript von materialize einbinden -->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>

<!-- Skript zum sotieren der Tabelle einbinden -->
<script type="text/javascript" src="js/TableSort.js"></script>

<!-- externes Skript einbinden (Funktionalität des Filterbereichs und der Tabelle) -->
<script src="js/extScript.js"></script>

<script>
//Übergabe des PHP Ergebnsis des Algorithmus an Javascript
<?php
$jsArray = json_encode($result);
echo ("var jsresult = $jsArray;");
$jsForecastPeriodYear = json_encode($forecastPeriodYear);
echo ("var jsForecastPeriodYear = $jsForecastPeriodYear;");
$jsLanguage = json_encode($lang->Main);
echo ("var jsLanguage = $jsLanguage;");
?>

//Aufruf der Methode, die Multiselect (Filter) erstellt und in HTML einfügt
buildFilter();
//Aufruf der Methode, die dem Slider den maximalen Wert gibt
setMaxForecastPeriod();
//Aufruf der Methode, die die Tabelle erstellt
buildTable();
//Filterbereich sichtbar machen
document.getElementById("filterRow").style.visibility = "visible";
</script>
</html>
