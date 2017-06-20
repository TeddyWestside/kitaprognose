<!--
author: Johannes Kusber
description: Über diese Datei wird das Impressum eingebunden
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
    <h1>Impressum</h1>
    <h2>Angaben gemäß § 5 TMG:</h2>
    <p>Carsten Schober<br /> Wodanstraße 7b<br /> 45891 Gelsenkirchen </p>
    <h2>Kontakt:</h2>
    <p>E-Mail: carstenschober93@googlemail.com
      <h2>Haftung für Inhalte</h2>
      <p>Als Diensteanbieter sind wir gemäß $ 7 Abs.1 TMG für eigene Inhalte auf diesen
        Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind
        wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte
        fremde Informationen zu +berwachen oder nach Umständen zu forschen, die
        auf eine rechtswidrige Tätigkeit hinweisen.
      </p>
      <p>Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen
        nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche
        Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten
        Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen
        werden wir diese Inhalte umgehend entfernen.</p>
        <h2>Haftung für Links</h2>
        <p>Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren
          Inhalte wir keinen Einfluss haben. Deshalb können wir für diese
          fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte
          der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der
          Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der
          Verlinkung auf mögliche Rechtsverstöße überprüft.
          Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar.
        </p>
        <p>Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch
          ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei
          Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend
          entfernen.
        </p>
        <h2>Urheberrecht</h2>
        <p>Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten
          unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung,
          Verbreitung und jede Art der Verwertung außerhalb der Grenzen des
          Urheberrechtesbedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw.
          Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten,
          nicht kommerziellen Gebrauch gestattet.
        </p>
        <p>Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden,
          werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter
          als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung
          aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von
          Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.
        </p>
        <p>Quelle: <a href="https://www.e-recht24.de">erecht24.de</a></p>
      </div>

      <!--Einbindung des Footers -->
      <?php include('footer.php'); ?>
    </body>

    </html>
