<!--
author: Johannes Kusber
description: Über diese Datei wird die Prognose parametriesiert, Filter erstellt,
der Prognosealgorithmus aufgerufen und das Ergebnis angezeigt. Der Header und der
Footer wird jeweils extra eingebunden.
-->

<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kitaprognose</title>

  <!--Import Google Icon Font-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Import materialize.css-->
  <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

  <?php
  //Definierung der Variablen und Vorbelegung mit Standardwerten
  //Standardsprache
  $lang = "de";
  //Standard % Anzahl Kinder im Kindergarten
  $propChildren = 100;
  //Standard % Geburtenrate
  $birthrate = 1.7;

  //Prüfen und ggf. Anpassen der Werte auf Grundlage der GET-Parameter
  if (isset($_GET["lang"])) {
    $lang = $_GET["lang"];
  }
  if (isset($_GET["propChildren"]) && $_GET["propChildren"] != "") {
    $propChildren = $_GET["propChildren"];
  }
  if (isset($_GET["birthrate"]) && $_GET["birthrate"] != "") {
    $birthrate = $_GET["birthrate"];
  }
  //Only fot test
  //var_dump($lang);
  //var_dump($propChildren);
  //var_dump($birthrate);
  //var_dump($forecastPeriod);

  //Einbindung der language.php Datei um Sprachunabhängigkeit in der GUI zu ermöglichen.
  require ('lang\language.php');
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
              <div class="row">
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
  include 'connection.php';
  require "phpClass/Algorithmus.php";

  $algo = new Algorithmus;
  $result = $algo->getPrognose($propChildren, $birthrate);
  ?>

  <script>

  // Inizialisierung
  <?php
  $jsArray = json_encode($result);
  echo ("var jsresult = $jsArray;");
  ?>

  buildFilter();
  setMaxForecastPeriod();
  buildTable();

  // Methoden

  // Beschreibung der Methode
  function buildTable(){
    tableArray = jsresult;
    // Bei nur änderung von der Prognosezeit wird auch das gesamte Filterarray neu geladen
    forecastPeriod = getForecastPeriod();
    filterArray = getFilterArray();
    if (filterArray != null){
      tableArray = buildFilteredArray(jsresult, getFilterArray());
    }

    var htmlString = "<table class='striped sortierbar' id='tableForecast'><thead><tr><th class='sortierbar'>Stadtteil</th><th class='sortierbar'>Auslastung</th></tr></thead><tbody>";
    var stadtteil;
    for (stadtteil in tableArray){
      htmlString = htmlString + "<tr><td>" + stadtteil + "</td><td class='" + outputColor(tableArray[stadtteil][forecastPeriod]) + "'>" + tableArray[stadtteil][forecastPeriod] + "</td></tr>"
    }
    htmlString = htmlString + "</tbody></table>";
    document.getElementById("tablePlaceholder").innerHTML = htmlString;

    var zumSortieren = document.getElementById("tableForecast");
    new JB_Table(zumSortieren);
  }

  // Beschreibung der Methode
  function outputColor (value){
    var color = "";
    if (value < 85){
      color = "red-text";
    } else if(value < 95){
      color = "orange-text";
    } else if(value <= 105){
      color = "green-text";
    } else if(value <= 115){
      color = "orange-text";
    } else{
      color = "red-text";
    }
    return color;
  }

  // Beschreibung der Methode
  function buildFilter() {
    var htmlString = "<option disabled selected>Alle</option>";

    for (stadtteil in jsresult){
      htmlString = htmlString + "<option>" + stadtteil + "</option>";
    }
    document.getElementById("filter").innerHTML = htmlString;
  }

  // Beschreibung der Methode
  function setMaxForecastPeriod() {
    var maxlength = 0;
      for (stadtteil in jsresult){
        var actuallength = jsresult[stadtteil].length;
        if(actuallength > maxlength){
          maxlength = actuallength;
        }
      }
    document.getElementById("forecastPeriod").max = maxlength;
  }

  // Beschreibung der Methode
  $(document).ready(function() {
    $('select').material_select();
  });

  // Beschreibung der Methode
  function getForecastPeriod() {
    return parseInt(document.getElementById("forecastPeriod").value) - 1;
  }

  // Beschreibung der Methode
  function getFilterArray (){
    var filterArray = new Array;
    var filterArrayPos = 0;
    var element = document.getElementById("filter");

    for(var i = 1; i < element.options.length; i++){
      if(element.options[i].selected){
        filterArray[filterArrayPos] = element.options[i].text;
        filterArrayPos = filterArrayPos + 1;
      }
    }
    // console.log("Das filter Array ist: ");
    // console.log(filterArray);
    if (filterArray.length == 0){
      // console.log("null return filter Array");
      return null;
    }
    return filterArray;
  }

  // Beschreibung der Methode
  function buildFilteredArray (oldArray, filterArray){
    var resultArray = new Array();


    // for(stadtteil in oldArray){
    //   for (var i = 0; i < filterArray.length; i++){
    //     if(stadtteil == filterArray[i]){
    //       console.log(stadtteil + "ist gleich" + filterArray[i]);
    //     }
    //   }
    // }
    for(stadtteil in oldArray){
      // console.log("Stadteil oldArray ist: " + stadtteil);
      for (var i = 0; i < filterArray.length; i++){
        // console.log("Stadteil FilterArray ist: " + filterArray[i]);
        if(stadtteil == filterArray[i]){
          // console.log("erfolgreich");
          // console.log(stadtteil + " ist gleich " + filterArray[i]);
          resultArray[stadtteil] = oldArray[stadtteil];
          // working müsste über iteration gelöst werden
          // new Array(oldArray[stadtteil][0], oldArray[stadtteil][1], oldArray[stadtteil][2])
        }
      }
    }
    // console.log(resultArray);
    return resultArray;
  }
  </script>
  </html>
