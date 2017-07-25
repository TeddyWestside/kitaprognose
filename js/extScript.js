//In dieser Klasse wird das Javascript, dass für die Index.php benötigt wird gesammelt definiert

//Diese Methode dient zur Initialisierung des Multiselects
$(document).ready(function() {
  $('select').material_select();
});

//Methode, die die Tabelle erstellt
function buildTable(){
  //Erstellung der benötigten Variablen
  //Enthält das Ergebnis
  tableArray = jsresult;
  //Enthält das aktuell eingestellte Vorhersagejahr
  forecastPeriod = getForecastPeriod();
  //Enthält die aktuell selektierten Stadtteile im Multiselect
  filterArray = getFilterArray();
  //Updatet das Ergebnis falls Stadtteile selektiert wurden
  if (filterArray != null){
    tableArray = buildFilteredArray(jsresult, getFilterArray());
  }
  //HTML Ergebnis zur Erstellung der Tabelle
  var htmlString = "<table class='striped sortierbar' id='tableForecast'><thead><tr><th class='sortierbar'>" + jsLanguage.label_first_table_row + "</th><th class='sortierbar'>" + jsLanguage.label_second_table_row + "</th></tr></thead><tbody>";
  //Stadtteil zum Speichern während der Iterationen
  var stadtteil;
  //Iteration über das Ergebnis um Tabellenzeilen zu bilden
  for (stadtteil in tableArray){
    htmlString = htmlString + "<tr><td>" + stadtteil + "</td><td class='" + outputColor(tableArray[stadtteil][forecastPeriod]) + "'>" + tableArray[stadtteil][forecastPeriod] + "</td></tr>"
  }
  //Abschluss des HTML Ergebnis zur Erstellung der Tabelle
  htmlString = htmlString + "</tbody></table>" + jsLanguage.text_tableCaption;
  //Manipulation des HTML Elements um fertige Tabelle einzufügen
  document.getElementById("tablePlaceholder").innerHTML = htmlString;
  //Manuelles aufrufen des Tablesort JS um Tabelle sortierbar zu machen
  var zumSortieren = document.getElementById("tableForecast");
  new JB_Table(zumSortieren);

  //Update des Vorhersagejahrs im Slider
  document.getElementById("forecastPeriodYear").innerHTML = jsForecastPeriodYear[forecastPeriod];
}

//Methode die für das Vorhersagejahr den Wert des Sliders zieht
function getForecastPeriod() {
  return parseInt(document.getElementById("forecastPeriod").value) - 1;
}

//Methode die alle im Multiselect selektierten Stadtteile zurück gibt
function getFilterArray (){
  //Variablen zum Speichern
  var filterArray = new Array;
  var filterArrayPos = 0;
  //Speichert das HTML Ergebnis
  var element = document.getElementById("filter");
  //Iteration durch das HTML Ergebnis und speichern der selektierten Werte in das Ergebnisarray
  for(var i = 1; i < element.options.length; i++){
    if(element.options[i].selected){
      filterArray[filterArrayPos] = element.options[i].text;
      filterArrayPos = filterArrayPos + 1;
    }
  }
  //Falls kein Wert selektiert wird (Alle) wird null zurückgegeben
  if (filterArray.length == 0){
    return null;
  }
  //Sonst wird das Ergebnisarray zurückgegeben
  return filterArray;
}

//Methode die aus dem Array mit den gefilterten Werten und dem ursprünglichen ein Resultat mit nur den im Filter selktierten erstellt
function buildFilteredArray (oldArray, filterArray){
  //zum Speichern des Ergebnisses
  var resultArray = new Array();
  //Iteration über ungefiltertes Array
  for(stadtteil in oldArray){
    //Vergleich ob Wert mit dem im gefilterten übereinstimmt, dann hinzugefügt zum Ergebnisarray
    for (var i = 0; i < filterArray.length; i++){
      if(stadtteil == filterArray[i]){
        resultArray[stadtteil] = oldArray[stadtteil];
      }
    }
  }
  return resultArray;
}

//Methode, die die Elemente für den Multiselect (Filter) erstellt und in HTML einfügt
function buildFilter() {
  //Ergebnisstring mit Beginn für den Fall, dass alle Elemente selektiert sind
  var htmlString = "<option disabled selected>" + jsLanguage.text_all_selected + "</option>";
  //Iteration über das Ergebnis und je Ergebnis (Stadtteil) wird eine Checkbox erstellt
  for (stadtteil in jsresult){
    htmlString = htmlString + "<option>" + stadtteil + "</option>";
  }
  //Update des Multiselect in HTML
  document.getElementById("filter").innerHTML = htmlString;
}

//Methode, die dem Slider den maximalen Wert gibt abhängig von der Anzahl der übergebenen Jahre aus dem Ergebnis damit dies nicht an mehreren Stellen im Code angepasst werden muss
function setMaxForecastPeriod() {
  var maxlength = 0;
    //Iteration über der einzelnen Ergebnisse und suche nach der längsten Vorhersagedauer
    for (stadtteil in jsresult){
      var actuallength = jsresult[stadtteil].length;
      if(actuallength > maxlength){
        maxlength = actuallength;
      }
    }
  //Update des Sliders auf maximale Vorhersagedauer
  document.getElementById("forecastPeriod").max = maxlength;
}

//Methode, die das Ergebnis abhängig von Skala einfärbt
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
