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
  //Standard Filterjahr
  $progYear = 0;

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

  //Einbindung der language.php Datei um Sprachunabhängigkeit in der GUI zu ermöglichen.
  require ('lang\language.php');
  /*Instanzierung der language-Klasse und speichern der JSON-Variable in $lang um auf die Strings über
  die IDs zugreifen zu können.
  */
  $language = new language($lang);
  $lang = $language->translate();
  ?>

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
                     class="validate" title="Bitte einen . als Dezimalzeichen nutzten  z.B. 95.99">
                    <label for="propChildren"><?php echo $lang->Main->label_propChildren ?></label>
                  </div>
                  <div class="input-field col l6">
                    <input placeholder=<?php echo $birthrate ?> id="birthrate" name="birthrate" type="text"
                    pattern="\d{3}|\d{3}\.\d|\d{3}\.\d{2}|\d{2}|\d{2}\.\d|\d{2}\.\d{2}|\d|\d\.\d|\d\.\d{2}"
                     class="validate" title="Bitte einen . als Dezimalzeichen nutzten  z.B. 95.99">
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
            <span class="card-title">Filter</span>
            <span>Radio Buttons?
            </span>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col l12">
          <div class="card-panel">
             <!-- Skript zum sotieren der Tabelle -->
             <script type="text/javascript" src="js/TableSort.js"></script>

              <?php
              include 'connection.php';
              require "phpClass/Algorithmus.php";

              $algo = new Algorithmus;
              $result = $algo->getPrognose($propChildren, $birthrate);

              echo "
              <table class='striped sortierbar'>
                <thead>
                  <tr>
                    <th class='sortierbar'>Stadtteil</th>
                    <th class='sortierbar'>Auslastung</th>
                  </tr>
                </thead>
              <tbody>";
              foreach($result as $stadtteil => $year){
                echo"<tr> <td>" . $stadtteil . "</td> <td style='color:" . outputColor($year[$progYear]) . "'>" . $year[$progYear] . "</td></tr>";
                //echo"<tr style='background-color:" . outputColor($year[$progYear]) ."'> <td>" . $stadtteil . "</td> <td>" . $year[$progYear] . "</td></tr>";
              }
              echo"
              </tbody>
              </table>";
              echo $lang->Main->text_tableCaption;


              function outputColor ($value){
                $color;
                if ($value < 85){
                  $color = "red";
                } else if($value < 95){
                  $color = "orange";
                } else if($value <= 105){
                  $color = "green";
                } else if($value <= 115){
                  $color = "orange";
                } else{
                  $color = "red";
                }

                return $color;
              }
              ?>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!--Einbindung des Footers -->
  <?php include('footer.php'); ?>
</body>
</html>
