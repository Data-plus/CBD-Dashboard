<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<title>MAP</title>
<meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.53.1/mapbox-gl.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.53.1/mapbox-gl.css' rel='stylesheet' />

<style>
body { margin:0; padding:0; }
#map { position:absolute; top:0; bottom:0; width:100%; }
</style>
</head>
<body>


<style>
    #menu {
    background: #fff;
    position: absolute;
    z-index: 2;
    top: 60px;
    right: 10px;
    border-radius: 3px;
    width: 120px;
    border: 1px solid rgba(0,0,0,0.4);
    font-family: 'Open Sans', sans-serif;
    }

    #menu a {
    font-size: 13px;
    color: #404040;
    display: block;
    margin: 0;
    padding: 0;
    padding: 10px;
    text-decoration: none;
    border-bottom: 1px solid rgba(0,0,0,0.25);
    text-align: center;
    }

    #menu a:last-child {
    border: none;
    }

    #menu a:hover {
    background-color: #f8f8f8;
    color: #404040;
    }

    #menu a.active {
    background-color: #7bb8e4;
    color: #ffffff;
    }

    #menu a.active:hover {
    background: #3074a4;
    }
    </style>

<script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v3.1.6/mapbox-gl-geocoder.min.js'></script>
<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.53.1/mapbox-gl.js'></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v3.1.6/mapbox-gl-geocoder.css' type='text/css' />
<style>
.geocoder {
    position:absolute;
    z-index:1;
    width:100%;
    left:50%;
    margin-left:-25%;
    top:20px;
}
.mapboxgl-ctrl-geocoder { min-width:100%; }
</style>

<style type='text/css'>
    #info {
    display: block;
    position: relative;
    margin: 0px auto;
    width: 50%;
    padding: 10px;
    border: none;
    border-radius: 3px;
    font-size: 12px;
    text-align: center;
    color: #222;
    background: #fff;
    }
</style>



<div id='geocoder' class='geocoder'></div>
<nav id='menu'></nav>
<div id='map'></div>
<pre id='info'></pre>




<script>
mapboxgl.accessToken = 'pk.eyJ1IjoicGx1c21nIiwiYSI6ImNqdGwxb3kxNjAwdmo0YW8xdjM4NG9zZWwifQ.Z9-QBnfpJDefBW7VzvC4mA';
var map = new mapboxgl.Map({
container: 'map', // container id
style: 'mapbox://styles/plusmg/cjtwy2na22bey1gt3tw5hf1ul', // stylesheet location
center: [144.9610735, -37.81359799], // starting position [lng, lat]
zoom: 15 // starting zoom
});

// Using local file
var url = {{ geojson_data }};

map.on('load', function () {
    map.addSource('art_gallery', {
         type: 'geojson',
         data: url
    }),

    map.addLayer({
    'id': 'art-gallery',
    'source': 'art_gallery',
    'type': 'circle',
    'paint': {
      'circle-radius': 4,
      'circle-color': 'rgba(0,255,0,1)'
    }
    });
});

// Using mapbox api
map.on('load', function () {

    map.addLayer({
        'id': 'transit_stop_label',
        'type': 'line',
        'source': {
            type: 'vector',
            url: 'mapbox://mapbox.mapbox-streets-v8'
        },
        'source-layer': 'transit_stop_label',
        'type': 'circle',
        'paint': {
        'circle-radius': 4,
        'circle-color': 'rgba(255,170,170,1)'
        }
    })
});

// For geocoder
map.on('load', function() {
    map.addSource('single-point', {
      type: 'geojson',
      data: {
        type: 'FeatureCollection',
        features: []
      }
    });

    map.addLayer({
      id: 'point',
      source: 'single-point',
      type: 'circle',
      paint: {
        'circle-radius': 10,
        'circle-color': 'rgba(255,203,49,1)'
    }
});


map.on('click', function(e) {
        var features = map.queryRenderedFeatures(e.point, {
            layers: ['public-art', 'cafe']
        });
        if (!features.length) {
            return;
        }
        var feature = features[0];
        var popup = new mapboxgl.Popup({ offset: [0, -15] })
            .setLngLat(feature.geometry.coordinates)
            .setHTML('<h4>' + feature.properties.Artist + '</h4><p>' + feature.properties.Name + '<p>' + feature.properties['Art Date'] + '</p>')
            .setLngLat(feature.geometry.coordinates)
            .addTo(map);
        });
var toggleableLayerIds = [ 'transit_stop_label', 'public-art', 'cafe', 'art-gallery'];



// Click
map.on("click", function (e) {
    document.getElementById('info').innerHTML =
    JSON.stringify(e.lngLat);
    var location = JSON.stringify(e.lngLat);
    console.log(location);

    $.ajax(
            { type : 'POST',
              url : "/test",
              contentType: "application/json;charset=UTF-8",
              dataType:'json',
              data : JSON.stringify({'data':location})
            }
          );
});





for (var i = 0; i < toggleableLayerIds.length; i++) {
    var id = toggleableLayerIds[i];

    var link = document.createElement('a');
    link.href = '#';
    link.className = 'active';
    link.textContent = id;

    link.onclick = function (e) {
        var clickedLayer = this.textContent;
        e.preventDefault();
        e.stopPropagation();

        var visibility = map.getLayoutProperty(clickedLayer, 'visibility');

        if (visibility === 'visible') {
            map.setLayoutProperty(clickedLayer, 'visibility', 'none');
            this.className = '';
        } else {
            this.className = 'active';
            map.setLayoutProperty(clickedLayer, 'visibility', 'visible');
        }
    };

    var layers = document.getElementById('menu');
    layers.appendChild(link);
};


// Add zoom and rotation controls to the map.
var nav = new mapboxgl.NavigationControl();
map.addControl(nav, 'top-left');

// Search
var geocoder = new MapboxGeocoder({ // Initialize the geocoder
    accessToken: mapboxgl.accessToken, // Set the access token
    placeholder: 'Search for Art Gallery',
    countries: 'au'
});

// Add actual geocoder (search bar)
map.addControl(geocoder);

// Listen for the `result` event from the Geocoder
// `result` event is triggered when a user makes a selection
// Add a marker at the result's coordinates
geocoder.on('result', function(e) {
      map.getSource('single-point').setData(e.result.geometry);
      var clickLog = console.log(e);
    });
});

</script>


</body>
</html>



