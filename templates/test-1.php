<!DOCTYPE html>
<html lang="en">
<head>
  <title>Gallevisionery</title>
  <meta charset="utf-8">
  <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
  <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.54.0/mapbox-gl.js'></script>
  <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.54.0/mapbox-gl.css' rel='stylesheet' />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v3.1.6/mapbox-gl-geocoder.min.js'></script>
  <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v3.1.6/mapbox-gl-geocoder.css' type='text/css' />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
  <script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>

  <link rel="stylesheet" type="text/css" href="../static/css/style.css">

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





<script>

function reloadImg() {
  var d=new Date();
  document.getElementById("HousePic").src="/image/house?a="+d.getTime();
  document.getElementById("PedPic").src="/image/pedestrian?a="+d.getTime();
  document.getElementById("CafePic").src="/image/cafe?a="+d.getTime();
  document.getElementById("CarPic").src="/image/carpark?a="+d.getTime();
  document.getElementById("AccPic").src="/image/accessible?a="+d.getTime();
}


var clicked = false;
    
    function countClicks()
    {
       if(clicked)
       {
        alert("Second Click"); 
          clicked = false;
       }
      else
      {
        reloadImg(); 
         clicked = true;
      }
    }

// Update Image Data

</script>

</head>

<body>
<!-- Map Box -->
<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-6 sidenav" id="map_area" onclick="countClicks()">
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
          'circle-radius': 5,
          'circle-color': 'rgba(255,255,104,1)'
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
          'circle-radius': 4,
          'circle-color': 'rgba(226,133,229,1)'
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
      map.addControl(nav, 'bottom-right');

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


    <!-- Left Side -->
    <!-- Location A -->
    <div class="col-sm-3">
      <div class="row-sm-4">
        <h2>Locaion A</h2>
        <hr>
        <h5><span class="glyphicon glyphicon-plus"></span><b> Clicked Location: 111 Elizabeth St</b></h5>
        <h5><span class="label label-danger">Data</span> <span class="label label-primary">Science</span></h5><br>
      </div>

      <!-- Icons -->
      <div class="row-sm-4">
        <h5><span class="glyphicon glyphicon-plus"></span> <b>Quick Stats!</b></h5>
        <hr>
        <img id="HousePic"> <img id="PedPic">  <img id="CafePic">  <img id="CarPic">  <img id="AccPic">
        <hr>
      </div>

      <!-- Chart -->
      <div style="text-align: center;" class="row-sm-4 ct-chart" >
        <h4><span class="label label-primary" >Pedestrian Counts</span></h4>
        <script>
          var myChart;
          var getData = $.get('/data');
          getData.done(function(results){
            var data = {
              labels: ['Sun', 'Mon', 'Tue', ' Wed', ' Thu', ' Fri', 'Sat'],
              series :[
                results.results
              ]
            };
            var options = {
              showPoint: false,
              lineSmooth: true,
              width : 400,
              height : 300
            }
            myChart = new Chartist.Line('.ct-chart', data, options);
          });
        function updateChart(){
          var updateData = $.get('/data');
          updateData.done(function(results){
              var data = {
                labels: ['Sun', 'Mon', 'Tue', ' Wed', ' Thu', ' Fri', 'Sat'],
                series :[
                  results.results
                ]
              };
            myChart.update(data);
            });
          }
        
          $("#map_area").on('click', updateChart);



        </script>
      </div>

    </div>
    
    <!-- Right Side -->
    <div class="col-sm-3" >
      <!-- Location B -->
      <div class="row-sm-4">
        <h2>Locaion B</h2>
        <hr>
        <h5><span class="glyphicon glyphicon-plus"></span> <b> Clicked Location: 222 Collins St</b></h5>
        <h5><span class="label label-danger">Hawawa</span> <span class="label label-primary">Testing</span></h5><br>
        <canvas id="lineChart" height="300" width="455"></canvas>
        <script src="../static/js/lineChart.js"></script>


      </div>

      <div class="row-sm-4">
        <h4><small>RECENT POSTS</small></h4>
        <hr>
        <h5><span class="glyphicon glyphicon-time"></span> Post by me, 20, April, 2019.</h5>
        <h5><span class="label label-success">Lorem</span></h5><br>
        <h5>This place will be Location B</h5>
        <hr>      
      </div>

      <div class="row-sm-4">
        <h4>Leave a Comment:</h4>
        <p>nisi ut aliquip ex ea commodo consequat. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
       </div>
      
    </div>
  </div>
</div>





</body>
</html>




