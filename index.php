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
  <title>Kitaprognose</title>

  <!-- Importieren der Material Icons von Google. Vorteil diese nicht lokal zur
  Verfügung zu stellen ist es das diese eventuell bereits im Cache des Browsers
  liegen. Des Weiteren Import des CSS-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

  <?php
  //Laden der Konfigurationsparameter, die zentral in der config.php festgelegt werden
  $config = include "config.php";
  //Definierung der Standardsprache
  $lang = $config["lang"];
  //Definierung Standard % Anzahl Kinder im Kindergarten
  $propChildren = $config["propChildren"];
  //Definierung Standard % Geburtenrate
  $birthrate = $config["birthrate"];

  //Prüfen der Get-Paramenter und falls vorhanden Anpassen der Werte
  if (isset($_GET["lang"])) {
    $lang = $_GET["lang"];
  }
  if (isset($_GET["propChildren"]) && $_GET["propChildren"] != "") {
    $propChildren = $_GET["propChildren"];
  }
  if (isset($_GET["birthrate"]) && $_GET["birthrate"] != "") {
    $birthrate = $_GET["birthrate"];
  }

  //Einbindung der language.php Datei um Sprachunabhängigkeit in der GUI zu ermöglichen.
  require ('lang\language.php');

// bis hierhin


  /*Instanzierung der language-Klasse und speichern der JSON-Variable in $lang um auf die Strings über
  die IDs zugreifen zu können.
  */
  $language = new language($lang);
  $lang = $language->translate();
  ?>

  <!-- Skript zum sotieren der Tabelle -->
  <script type="text/javascript" src="TableSort.js"></script>
</head>
<body>
  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="js/materialize.min.js"></script>

  <!--Einbindung des Headers -->
  <?php include('header.php'); ?>

  <main>
    <div class="container">
      <ul class="collapsible" data-collapsible="accordion">
        <li>
          <div class="collapsible-header"><i class="material-icons">mode_edit</i><?php echo $lang->Main->title_parameterConfig ?></div>
          <div class="collapsible-body">
            <div class="row">
              <form class="col l12" method="get">
                <!-- Get nicht verkettet -->
                <div class="row">
                  <?php echo $lang->Main->text_parameterConfig?>
                </div>
                <div class="row">
                  <div class="input-field col l6">
                    <input placeholder=<?php echo $propChildren ?> id="propChildren"
                    name="propChildren" type="text"
                    pattern="100|100\.00|100\.0|\d{2}|\d{2}\.\d|\d{2}\.\d{2}|\d|\d\.\d|\d\.\d{2}"
                    class="validate" title="<?php echo $lang->Main->title_validation ?>">
                    <label for="propChildren"><?php echo $lang->Main->label_propChildren ?></label>
                  </div>
                  <div class="input-field col l6">
                    <input placeholder=<?php echo $birthrate ?> id="birthrate" name="birthrate" type="text"
                    pattern="\d{3}|\d{3}\.\d|\d{3}\.\d{2}|\d{2}|\d{2}\.\d|\d{2}\.\d{2}|\d|\d\.\d|\d\.\d{2}"
                    class="validate" title="<?php echo $lang->Main->title_validation ?>">
                    <label for="birthrate"><?php echo $lang->Main->label_birthrate ?></label>
                  </div>
                </div>
                <div class="row">
                  <button type="submit" class="waves-effect waves-light btn col l12"><?php echo $lang->Main->text_parameterButton ?></button>
                </div>
              </form>
            </div>
          </div>
        </li>
      </ul>

        <div class="row">
          <div class="col l12">
            <div class="card-panel">
              <div class="row" id="filterRow" style="visibility:hidden">
                <div class="input-field col l6">

                  <select id="filter" multiple onchange="buildTable();">
                  </select>
                  <label>Stadtteile filtern:</label>

                </div>
                <div class="col l6">
                  <label>Prognose in:</label>
                  <input type="range" id="forecastPeriod" onchange="buildTable()"  value="1" min="1">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col l12">
            <div class="card-panel">
              <span id="tablePlaceholder">
              </span>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!--Einbindung des Footers -->
    <?php include('footer.php'); ?>
  </body>

  <?php
  echo "<span style='position:absolute; top: 0; background: red'>";
  include 'connection.php';
  if($GLOBALS['conn'] != null){
    require "phpClass/Algorithmus.php";
    $algo = new Algorithmus;
    $result = $algo->getPrognose($propChildren, $birthrate);
  }
  else{
    die();
  }
  echo "</span>";
  ?>
  <script src="js/extScript.js"></script>
  <script>
  // Inizialisierung
  <?php
  $jsArray = json_encode($result);
  echo ("var jsresult = $jsArray;");
  $jsLanguage = json_encode($lang);
  echo ("var jsLanguage = $jsLanguage;");
  ?>

  // for test
  console.log(jsLanguage);
  console.log("Ich habe das bis hier ausgeführt");

  buildFilter();
  setMaxForecastPeriod();
  buildTable();
  document.getElementById("filterRow").style.visibility = "visible";
  </script>
  </html>
