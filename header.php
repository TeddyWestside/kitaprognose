<!--
author: Johannes Kusber
description: In der header.php ist die Navigation ausgelagert damit diese auf mehreren Seiten eingebunden werden kann
-->
<!-- Definierung des Bereichs als Header damit er immer oben eingebunden wird -->
<header>
  <!-- Erstellen der Navigation -->
  <nav>
    <div class="container">
      <div class="nav-wrapper">
        <!-- Erstellen des Logos (in unserem Fall ein Text) -->
        <a href="index.php" class="brand-logo"><?php echo $lang->Basic->program_Name; ?></a>
        <!-- Erstellen der Sprachauswahl und Anordnung rechts -->
        <ul id="nav-mobile" class="right">
          <li><a class='dropdown-button' href='#' data-activates='dropdownLanguage'><?php echo $lang->Basic->language ?></a></li>
        </ul>
        <!-- Dropdown Struktur -->
        <ul id='dropdownLanguage' class='dropdown-content'>
          <li><a href="./?langCode=de">Deutsch</a></li>
          <!--Als Sprache 2 ist aktuell "en" festgelegt um die Funktion zu testen. Es
          sind jedoch nich alle IDs codiert wodurch Fehler in der GUI entstehen können.
          Um eine weitere Sprache hinzuzufügen muss ein li Block dupliziert werden, der
          langCode (de->fr) angepasst werden und der Name der Sprache (Deutsch->français)
          eingegeben werden-->
          <li><a href="./?langCode=en">Englisch</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>
