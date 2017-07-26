<!--
author: Johannes Kusber
description: Über diese Datei wird die Datenschutzerklärung eingebunden
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
  //Prüfen der Get-Paramenter in URL und falls vorhanden Anpassung der Werte
  if (isset($_GET["langCode"])) {
    $langCode = $_GET["langCode"];
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
  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="js/materialize.min.js"></script>

  <!--Einbindung des  Headers -->
  <?php include('header.php'); ?>
  <main>
    <div class="container">
      <h1><?php echo $lang->Footer->disclaimer; ?></h1>
      <h2><?php echo $lang->Disclaimer->heading_disclaimer; ?></h2>
        <p><?php echo $lang->Disclaimer->text_disclaimer1; ?></p>
        <p><?php echo $lang->Disclaimer->text_disclaimer2; ?></p>
        <p><?php echo $lang->Disclaimer->text_disclaimer3; ?></p>
      <h2><?php echo $lang->Disclaimer->heading_cookies; ?></h2>
        <p><?php echo $lang->Disclaimer->text_cookies1; ?></p>
        <p><?php echo $lang->Disclaimer->text_cookies2; ?></p>
        <p><?php echo $lang->Disclaimer->text_cookies3; ?></p>
      <p><?php echo $lang->Disclaimer->text_source; ?><a href="https://www.e-recht24.de/musterdatenschutzerklaerung.html">https://www.e-recht24.de/musterdatenschutzerklaerung.html</a></p>
    </div>
  </main>
  <!--Einbindung des Footers -->
  <?php include('footer.php'); ?>
</body>
</html>
