<!--
author: Johannes Kusber
description: In der footer.php ist der Footerbereich ausgelagert damit dieser auf mehreren Seiten eingebunden werden kann
-->
<!-- Definierung des Bereichs als Pagefooter damit er immer unten (aber über dem Copyrightfooter) eingebunden wird -->
<footer class="page-footer">
  <div class="container">
    <div class="row valign-wrapper">
      <!-- Erstellen des Titels und der Beschreibung -->
      <div class="col l6">
        <h5 class="white-text"><?php echo $lang->Basic->program_Name; ?></h5>
        <p class="grey-text text-lighten-4"><?php echo $lang->Footer->description ?></p>
      </div>
      <!-- Einbindung und Verlinkung der beiden Logos -->
      <div class="col l3 valign-wrapper">
        <a href="https://www.w-hs.de"><img class="responsive-img" src="./img/Logo-WHS.gif" alt="Logo WHS"></a>
      </div>
      <div class="col l3 valign-wrapper">
        <a href="https://www.gelsenkirchen.de/"><img class="responsive-img" src="./img/Logo-GE.gif" alt="Logo Stadt Gelsenkirchen"></a>
      </div>
    </div>
  </div>
  <!-- Definierung des Bereichs als Copyrightfooter damit er immer unten (aber unter dem Pagefooter) eingebunden wird -->
  <div class="footer-copyright">
    <div class="container">
      <div class="row">
        <!-- Einbindung der Daten der letzten Datenbestände -->
        <div class="col l4">
          <?php
            // $lr_datenbankabfrage = new Datenbankabfrage();
            // $la_letztes_update = $lr_datenbankabfrage->getZwischenspeicher();
            // echo $lang->Footer->last_dataset . $la_letztes_update[0]["Wert"];
            echo $lang->Footer->last_dataset;


           ?>
        </div>
        <!-- Einbindung des Impressum und der Datenschutzerklärung -->
        <div class="col l4">
          <a class="grey-text text-lighten-3" href="./impressum.php<?php echo "?langCode=" . $langCode ?>"><?php echo $lang->Footer->imprint ?></a>
          ||
          <a class="grey-text text-lighten-3" href="./datenschutzerklaerung.php<?php echo "?langCode=" . $langCode ?>"><?php echo $lang->Footer->disclaimer ?></a>
        </div>
        <!-- Einbindung "Slogan" -->
        <div class="col l4">
          Made with LOVE in Gelsenkirchen
        </div>
      </div>
    </div>
  </div>
</footer>
