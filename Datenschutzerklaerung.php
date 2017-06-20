<!--
author: Johannes Kusber
description: Über diese Datei wird die Datenschutzerklärung eingebunden
-->

<!-- Sprachabhängigkeit umstellen funktioniert nicht und Texte nicht sprachunabhängig-->

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

  //Prüfen und ggf. Anpassen der Werte auf Grundlage der GET-Parameter
  if (isset($_GET["lang"])) {
    $lang = $_GET["lang"];
  }

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

  <!--Einbindung des  Headers -->
  <?php include('header.php'); ?>

  <div class="container">
    <h1>Datenschutzerklärung:</h1>
    <h2>Datenschutz</h2>
    <p>Die Betreiber dieser Seiten nehmen den Schutz Ihrer persönlichen Daten sehr
      ernst. Wir behandeln Ihre personenbezogenen Daten vertraulich und entsprechend der
      gesetzlichen Datenschutzvorschriften sowie dieser Datenschutzerklärung.
    </p>
    <p>Die Nutzung unserer Webseite ist in der Regel ohne Angabe personenbezogener
      Daten möglich. Soweit auf unseren Seiten personenbezogene Daten (beispielsweise
      Name, Anschrift oder E-MailAdressen) erhoben werden, erfolgt dies, soweit
      möglich, stets auf freiwilliger Basis. Diese Daten werden ohne Ihre ausdrückliche
      Zustimmung nicht an Dritte weitergegeben.
    </p>
    <p>Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der
      Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz
      der Daten vor dem Zugriff durch Dritte ist nicht möglich.
    </p>
    <h2>Cookies</h2>
    <p>Die Internetseiten verwenden teilweise so genannte Cookies. Cookies richten
      auf Ihrem Rechner keinen Schaden an und enthalten keine Viren. Cookies dienen
      dazu, unser Angebot nutzerfreundlicher, effektiver und sicherer zu machen.
      Cookies sind kleine Textdateien, die auf Ihrem Rechner abgelegt werden und
      die Ihr Browser speichert.
    </p>
    <p>Die   meisten der von uns verwendeten Cookies sind so genannte „Session-Cookies“.
      Sie werden nach Ende Ihres Besuchs automatisch gelöscht. Andere Cookies bleiben auf
      Ihrem Endgerät gespeichert, bis Sie diese löschen. Diese Cookies ermöglichen es uns,
      Ihren Browser beim nächsten Besuch wiederzuerkennen.
    </p>
    <p>Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies
      informiert werden und Cookies nur im Einzelfall erlauben, die Annahme von
      Cookies für bestimmte Fälle oder generell ausschließen sowie das automatische
      Löschen der Cookies beim Schließen des Browser aktivieren. Bei der Deaktivierung
      von Cookies kann die Funktionalität dieser Website eingeschränkt sein.
    </p>
    <p>Quelle: <a href="https://www.e-recht24.de/musterdatenschutzerklaerung.html">https://www.e-recht24.de/musterdatenschutzerklaerung.html</a></p>
  </div>
  <!--Einbindung des Footers -->
  <?php include('footer.php'); ?>
</body>
</html>
