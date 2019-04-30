<!DOCTYPE html>
<html lang="en">
<head>
  <title>Gallevisionery</title>
  <meta charset="utf-8">

  <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
  <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.53.1/mapbox-gl.css' rel='stylesheet' />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.53.1/mapbox-gl.js'></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v3.1.6/mapbox-gl-geocoder.min.js'></script>
  <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.53.1/mapbox-gl.js'></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v3.1.6/mapbox-gl-geocoder.css' type='text/css' />
  <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.54.0/mapbox-gl.js'></script>
  <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.54.0/mapbox-gl.css' rel='stylesheet' />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
  <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

  
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
    .row.content {height: 100vh;}
    
    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #ffffff;
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      height: 100%;
      width:100%;
      z-index:1;
      padding: 25px; /*Footer*/
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 737px;) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height: auto;} 
    }

  .svgCanvas {
      border: solid 1px;
      width : 100%;
  }

  #menu {
    background: #fff;
    position: absolute;
    z-index: 2;
    top: 10px;
    left: 45px;
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



<style>
.geocoder {
  position:absolute;
  z-index:1;
  width:100%;
  left:50%;
  margin-left:-25%;
  top:20px;
}
.mapboxgl-ctrl-geocoder { 
  min-width:100%; 
  }
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

<style>
  .button {
  background-color: #555555; /* Black */
  border: none;
  color: white;
  padding: 10px 24px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 12px;
}
</style>


<style>
body { margin:0; padding:0;}
#map { position:absolute; top:0; bottom:0; left:0; width:100%; height:100vh;}
</style>

</head>

<body>

<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-6 sidenav">
      <h4>Mapbox</h4>
          
      <div id='geocoder' class='geocoder'></div>
      <nav id='menu'></nav>
      <div id='map'></div>
      <pre id='info'></pre>


      <script>
      mapboxgl.accessToken = 'pk.eyJ1IjoicGx1c21nIiwiYSI6ImNqdGwxb3kxNjAwdmo0YW8xdjM4NG9zZWwifQ.Z9-QBnfpJDefBW7VzvC4mA';

      // Set bounds to CBD
      var bounds = [
        [144.9437392829542, -37.82724080876474],  // Northeast coordinates
        [144.97613141562647, -37.8015691517381] // Southwest coordinates
      ];

      var map = new mapboxgl.Map({
      container: 'map', // container id
      style: 'mapbox://styles/plusmg/cjtwy2na22bey1gt3tw5hf1ul', // stylesheet location
      center: [144.9628079612438, -37.81370894743138], // starting position [lng, lat]
      zoom: 11, // starting zoom
      maxBounds: bounds // Sets bounds as max
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

      // Using local file
      var url2 = {{ sensors }};

      map.on('load', function () {
          map.addSource('sensor', {
              type: 'geojson',
              data: url2
          }),

          map.addLayer({
          'id': 'ped-sensor',
          'source': 'sensor',
          'type': 'circle',
          'paint': {
          'circle-radius': 5,
          'circle-color': 'rgba(255,0,170,1)'
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
      var toggleableLayerIds = [ 'transit_stop_label', 'public-art', 'cafe', 'art-gallery', 'ped-sensor'];



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
          countries: 'au',
          bbox: [144.94643659774766, -37.820382419529345, 144.9771965824976, -37.80410161391119]  // Northeast coordinates
      });

      // Add actual geocoder (search bar)
      map.addControl(geocoder);

      // Listen for the `result` event from the Geocoder
      // `result` event is triggered when a user makes a selection
      // Add a marker at the result's coordinates
      geocoder.on('result', function(e) {
          map.getSource('single-point').setData(e.result.geometry);
          var clickLog = JSON.stringify(e.result.geometry.coordinates);
          console.log(clickLog);

          $.ajax(
                  { type : 'POST',
                  url : "/test1",
                  contentType: "application/json;charset=UTF-8",
                  dataType:'json',
                  data : JSON.stringify({'data':clickLog})
                  }
              );


          });
      });
  

    </script>
    </div>


  <!–– Below is Right side area ––>

    
    <div class="col-sm-3">
        <div class="row-sm-4" style="background-color:lightcyan;"></div>
      <h2>Graph</h2>
      <hr>
      <h5><span class="glyphicon glyphicon-time"></span> Post by me, 20, April, 2019.</h5>
      <h5><span class="label label-danger">Food</span> <span class="label label-primary">Ipsum</span></h5><br>
      <button class="button", id="update">Update the Chart</button>



      <!-- Chart -->
      <div class="row-sm-4 ct-chart" >
      <script>
        var myChart;
        var getData = $.get('/data');

        getData.done(function(results){

          var data = {
            labels: ['Mon', 'Tue', ' Wed', ' Thu', ' Fri'],
            series :[
              results.results
            ]
          };

          var options = {
            width : 400,
            height : 300
          }
          myChart = new Chartist.Line('.ct-chart', data, options);
        });

      function updateChart(){

        var updateData = $.get('/data');

        updateData.done(function(results){

            var data = {
              labels: ['Mon', 'Tue', ' Wed', ' Thu', ' Fri'],
              series :[
                results.results
              ]
            };

          myChart.update(data);

          });
        }
      
        $("#update").on('click', updateChart);

      </script>
      </div>
      <div class="row-sm-4" style="background-color:lightcyan;">

      <h4><small>RECENT POSTS</small></h4>
      <hr>
      <h2>Hello World</h2>
      <h5><span class="glyphicon glyphicon-time"></span> Post by me, 20, April, 2019.</h5>
      <h5><span class="label label-success">Lorem</span></h5><br>
      <hr>
      
      <script>
        var chart_data = $.get('/test');


      </script>
      </div>
      </div>



      <div class="col-sm-3">
      <h2>Comparison</h2>
      <hr>
      <h2>Text</h2>
      <h5><span class="glyphicon glyphicon-time"></span> Post by me, 20, April, 2019.</h5>
      <h5><span class="label label-danger">Food</span> <span class="label label-primary">Ipsum</span></h5><br>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
      <br><br>

      <h4><small>RECENT POSTS</small></h4>
      <hr>
      <h5><span class="glyphicon glyphicon-time"></span> Post by me, 20, April, 2019.</h5>
      <h5><span class="label label-success">Lorem</span></h5><br>
      
        

      <hr>
      <h4>Leave a Comment:</h4>
      <form role="form">
        <div class="form-group">
          <textarea class="form-control" rows="3" required></textarea>
        </div>
      </div>


    </div>

</body>
</html>
