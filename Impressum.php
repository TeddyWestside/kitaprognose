<!--
author: Johannes Kusber
description: Über diese Datei wird das Impressum erstellt
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

  <!-- Erstellen des sprachabhängigen Impressums -->
  <main>
    <div class="container">
      <h1><?php echo $lang->Footer->imprint; ?></h1>
      <h2><?php echo $lang->Imprint->heading_information; ?></h2>
        <p>Carsten Schober<br /> Wodanstraße 7b<br /> 45891 Gelsenkirchen </p>
      <h2><?php echo $lang->Imprint->heading_contact; ?></h2>
        <p>E-Mail: carstenschober93@googlemail.com</p>
      <h2><?php echo $lang->Imprint->heading_liability_content; ?></h2>
        <p><?php echo $lang->Imprint->text_liability_content1; ?> </p>
        <p><?php echo $lang->Imprint->text_liability_content2; ?></p>
      <h2><?php echo $lang->Imprint->heading_liability_link; ?></h2>
        <p><?php echo $lang->Imprint->text_liability_link1; ?></p>
        <p><?php echo $lang->Imprint->text_liability_link2; ?></p>
      <h2><?php echo $lang->Imprint->heading_copyright; ?></h2>
        <p><?php echo $lang->Imprint->text_copyright1; ?></p>
        <p><?php echo $lang->Imprint->text_copyright2; ?></p>
        <p><?php echo $lang->Imprint->text_source; ?><a href="https://www.e-recht24.de">erecht24.de</a></p>
    </div>
  </main>

  <!--Einbindung des Footers -->
  <?php include('footer.php'); ?>
</body>

</html>
