<nav>
  <div class="container">
    <div class="nav-wrapper">
      <a href="#" class="brand-logo">Kitaprognose</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a class='dropdown-button' href='#' data-activates='dropdownLanguage'><?php echo $lang->Basic->language ?></a></li>
      </ul>
      <!-- Dropdown Structure -->
      <ul id='dropdownLanguage' class='dropdown-content'>
        <li><a href="./?lang=de">Deutsch</a></li>
        <!--Als Sprache 2 ist aktuell "en" festgelegt um die Funktion zu testen. Es
        sind jedoch nich alle IDs codiert wodurch Fehler in der GUI entstehen können. -->
        <li><a href="./?lang=en">Englisch</a></li>
      </ul>
    </div>
  </div>
</nav>
