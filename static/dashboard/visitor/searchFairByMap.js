/* https://www.w3schools.com/html/tryit.asp?filename=tryhtml5_geolocation_error */
var centerMap = [5.3378043, 50.9303735];

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, showError);
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
  centerMap = [position.coords.latitude, position.coords.longitude];
}

function showError(error) {
  //Hasselt
  centerMap = [0, 0];
}

function initMap() {
  mapboxgl.accessToken =
    "pk.eyJ1Ijoia2lyYW5zaW5naCIsImEiOiJja2hhZ2drY2IxZmU4MnBuejQ2ZGVoeGd5In0.gQH7wpT9LY26je46XhoXaw";

  getLocation();

  var map = new mapboxgl.Map({
    container: "map",
    style: "mapbox://styles/mapbox/outdoors-v11",
    center: centerMap,
    zoom: 6,
  });

  var geoJSON;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      geoJSON = JSON.parse(this.responseText);
      console.log(geoJSON);
      initGeoCoder(map, geoJSON);

      addFairLocationsToMap(map, geoJSON);
    }
  };
  xmlhttp.open(
    "GET",
    "../../../server/dashboard/visitor/searchByMapRequest.php",
    true
  );
  xmlhttp.send();
}

function initGeoCoder(map, geoJSON) {
  function forwardGeocoder(query) {
    var matchingFeatures = [];
    for (var i = 0; i < geoJSON.features.length; i++) {
      var feature = geoJSON.features[i];
      // handle queries with different capitalization than the source data by calling toLowerCase()
      if (
        feature.properties.title.toLowerCase().search(query.toLowerCase()) !==
        -1
      ) {
        // add a tree emoji as a prefix for custom data results
        // using carmen geojson format: https://github.com/mapbox/carmen/blob/master/carmen-geojson.md
        feature["place_name"] = "🎡 " + feature.properties.title;
        feature["center"] = feature.geometry.coordinates;
        feature["place_type"] = ["park"];
        matchingFeatures.push(feature);
      }
    }
    return matchingFeatures;
  }

  /* GeoCoder */
  var geocoder = new MapboxGeocoder({
    accessToken: mapboxgl.accessToken,
    localGeocoder: forwardGeocoder,
    mapboxgl: mapboxgl,
  });


  geocoder.on('results', function (results) {
    console.log(results);
  })

  map.addControl(geocoder);
}

function addFairLocationsToMap(map, geoJSON) {
  // add markers to map
  geoJSON.features.forEach(function (marker) {
    // create a HTML element for each feature
    var el = document.createElement("div");
    el.className = "marker";

    // make a marker for each feature and add to the map
    new mapboxgl.Marker(el)
      .setLngLat(marker.geometry.coordinates)
      .setPopup(
        new mapboxgl.Popup({
          offset: 0
        }) // add popups
        .setHTML(
          "<div>" +
          "<h3>" +
          marker.properties.title +
          "</h3><p>" +
          marker.properties.description +
          "</p></div>"
        )
      )
      .addTo(map);
  });
}
