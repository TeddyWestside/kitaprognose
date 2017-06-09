<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <title>Kitaprognose</title>

  <?php
    //Einbindung der language.php Datei um Sprachunabhängigkeit in der GUI zu ermöglichen.
    require ('lang\language.php');
    //Prüfung ob ein lang-Parameter übergeben wird. Falls nicht wird die Standardsprache Deutsch gesetzt.
    if (empty($_GET['lang'])) {
    $lang = 'de';
    } else {
    $lang = $_GET['lang'];
    }
    /*Instanzierung der language-Klasse und speichern der JSON-Variable in $lang um auf die Strings über
    die IDs zugreifen zu können.
    */
    $language = new language($lang);
    $lang = $language->translate();
  ?>

  <!--Import Google Icon Font-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Import materialize.css-->
  <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body>
  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="js/materialize.min.js"></script>

  <?php include('header.php'); ?>

  <main>
    <div class="container">
      <ul class="collapsible" data-collapsible="accordion">
        <li>
          <div class="collapsible-header"><i class="material-icons">mode_edit</i><?php echo $lang->Main->title_parameterConfig ?></div>
          <div class="collapsible-body">
            <div class="row">
              <form class="col l12" method="get">
                <div class="row">
                  <?php echo $lang->Main->text_parameterConfig?>
                </div>
                <div class="row">
                  <div class="input-field col s6">
                    <input placeholder="10" id="" type="text" class="validate">
                    <label for="xyz">XYZ</label>
                  </div>
                  <div class="input-field col s6">
                    <input placeholder="5" id="" type="text" class="validate">
                    <label for="zyx">ZYX</label>
                  </div>
                </div>
                <div class="row">
                  <button type="submit" class="waves-effect waves-light btn"><?php echo $lang->Main->text_parameterButton ?></button>
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
            <span>Filter here
            </span>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col l12">
          <div class="card-panel">
            <span>
              <?php include("Datenbankinitialisierung.php"); ?>
              <?php include("Algorithmus.php"); ?>
            </span>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!--Einbindung des Footers -->
  <?php include('footer.php'); ?>
</body>
</html>
