// Kommentar hier

// Beschreibung der Methode
$(document).ready(function() {
  $('select').material_select();
});

// Beschreibung der Methode
function buildTable(){
  tableArray = jsresult;
  // Bei nur änderung von der Prognosezeit wird auch das gesamte Filterarray neu geladen
  forecastPeriod = getForecastPeriod();
  filterArray = getFilterArray();
  if (filterArray != null){
    tableArray = buildFilteredArray(jsresult, getFilterArray());
  }

  var htmlString = "<table class='striped sortierbar' id='tableForecast'><thead><tr><th class='sortierbar'>Stadtteil</th><th class='sortierbar'>Auslastung</th></tr></thead><tbody>";
  var stadtteil;
  for (stadtteil in tableArray){
    htmlString = htmlString + "<tr><td>" + stadtteil + "</td><td class='" + outputColor(tableArray[stadtteil][forecastPeriod]) + "'>" + tableArray[stadtteil][forecastPeriod] + "</td></tr>"
  }
  htmlString = htmlString + "</tbody></table>";
  document.getElementById("tablePlaceholder").innerHTML = htmlString;

  var zumSortieren = document.getElementById("tableForecast");
  new JB_Table(zumSortieren);
}

// Beschreibung der Methode
function getForecastPeriod() {
  return parseInt(document.getElementById("forecastPeriod").value) - 1;
}

// Beschreibung der Methode
function getFilterArray (){
  var filterArray = new Array;
  var filterArrayPos = 0;
  var element = document.getElementById("filter");

  for(var i = 1; i < element.options.length; i++){
    if(element.options[i].selected){
      filterArray[filterArrayPos] = element.options[i].text;
      filterArrayPos = filterArrayPos + 1;
    }
  }
  // console.log("Das filter Array ist: ");
  // console.log(filterArray);
  if (filterArray.length == 0){
    // console.log("null return filter Array");
    return null;
  }
  return filterArray;
}

// Beschreibung der Methode
function buildFilteredArray (oldArray, filterArray){
  var resultArray = new Array();


  // for(stadtteil in oldArray){
  //   for (var i = 0; i < filterArray.length; i++){
  //     if(stadtteil == filterArray[i]){
  //       console.log(stadtteil + "ist gleich" + filterArray[i]);
  //     }
  //   }
  // }
  for(stadtteil in oldArray){
    // console.log("Stadteil oldArray ist: " + stadtteil);
    for (var i = 0; i < filterArray.length; i++){
      // console.log("Stadteil FilterArray ist: " + filterArray[i]);
      if(stadtteil == filterArray[i]){
        // console.log("erfolgreich");
        // console.log(stadtteil + " ist gleich " + filterArray[i]);
        resultArray[stadtteil] = oldArray[stadtteil];
        // working müsste über iteration gelöst werden
        // new Array(oldArray[stadtteil][0], oldArray[stadtteil][1], oldArray[stadtteil][2])
      }
    }
  }
  // console.log(resultArray);
  return resultArray;
}

// Beschreibung der Methode
function buildFilter() {
  var htmlString = "<option disabled selected>Alle</option>";

  for (stadtteil in jsresult){
    htmlString = htmlString + "<option>" + stadtteil + "</option>";
  }
  document.getElementById("filter").innerHTML = htmlString;
}

// Beschreibung der Methode
function setMaxForecastPeriod() {
  var maxlength = 0;
    for (stadtteil in jsresult){
      var actuallength = jsresult[stadtteil].length;
      if(actuallength > maxlength){
        maxlength = actuallength;
      }
    }
  document.getElementById("forecastPeriod").max = maxlength;
}

// Beschreibung der Methode
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
