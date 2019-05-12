<!DOCTYPE html>
<html lang="en">
<head>
  <title>Gallevisionery</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.54.0/mapbox-gl.js'></script>
  <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.54.0/mapbox-gl.css' rel='stylesheet' />
  <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v3.1.6/mapbox-gl-geocoder.min.js'></script>
  <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v3.1.6/mapbox-gl-geocoder.css' type='text/css' />
  <script src='https://npmcdn.com/@turf/turf/turf.min.js'></script>
  
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="../static/css/style.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
  

<style>
    .geocoder {
      position:absolute;
      z-index:1;
      width:100%;
      left:50%;
      margin-left:-25%;
      top:70px;       
}
.mapboxgl-ctrl-geocoder { 
      min-width:100%;
      top: 70px; 
      z-index:7;
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
  z-index:-1;
  }

</style>  





<script>
// Update Image Data
function reloadImg() {
  var d=new Date();

  // NEW SCRIPT (pedestrian deleted) - BZ
//   $.get("/test", function (r)){
//   if (r.cafe>20) {
//     document.getElementById(id="restaurantIcon") + d.getTime(); // + counts  
//   }
// }

  $.get("/test", function (r) {
  if (r.cafe > 20)
  then {
      document.getElementById(id="restaurantIcon");
      document.getElementById("restaurantIcon").title = r.cafe;
    } 
  else {
    document.getElementById(id="restaurantIcon").style.display='hide';
    // document.getElementById("restaurantIcon").title = r.cafe; 
  }
  })  


  // document.getElementById("HousePic")+d.getTime();
  // document.getElementById("restaurantIcon")+d.getTime();
  // document.getElementById("toiletIcon")+d.getTime();
  // document.getElementById("GalPic").src="/image/gallery?a="+d.getTime();
  // document.getElementById("PrintPic").src="/image/print?a="+d.getTime();
  // document.getElementById("artworkIcon")+d.getTime();
  // document.getElementById("parkingIcon")+d.getTime();

  // THIS IS THE ORIGINAL SCRIPT
  // document.getElementById("HousePic").src="/image/house?a="+d.getTime();
  // document.getElementById("PedPic").src="/image/pedestrian?a="+d.getTime();
  // document.getElementById("CafePic").src="/image/cafe?a="+d.getTime();
  // document.getElementById("AccPic").src="/image/accessible?a="+d.getTime();
  // document.getElementById("GalPic").src="/image/gallery?a="+d.getTime();
  // document.getElementById("PrintPic").src="/image/print?a="+d.getTime();
  // document.getElementById("PubPic").src="/image/pub?a="+d.getTime();
  // document.getElementById("parkingIcon").src="/image/carpark?a="+d.getTime();

  $.get("/address", function (data){
    document.getElementById(id="address1").innerHTML=data.address;
  });

  // $.get("/test", function (data) {
  //               document.getElementById("HousePic").title = data.residents;
  //               document.getElementById("PedPic").title = data.ped;
  //               document.getElementById("CafePic").title = data.cafe;
  //               document.getElementById("AccPic").title = data.accessible;
  //               document.getElementById("GalPic").title = data.gallery;
  //               document.getElementById("PrintPic").title = data.prints;
  //               document.getElementById("PubPic").title = data.pubs;

  //             });

};


function reloadImg2() {
  var d=new Date();




  // document.getElementById("HousePic2").src="/image/house?a="+d.getTime();
  // document.getElementById("PedPic2").src="/image/pedestrian?a="+d.getTime();
  // document.getElementById("CafePic2").src="/image/cafe?a="+d.getTime();
  // document.getElementById("AccPic2").src="/image/accessible?a="+d.getTime();
  // document.getElementById("GalPic2").src="/image/gallery?a="+d.getTime();
  // document.getElementById("PrintPic2").src="/image/print?a="+d.getTime();
  // document.getElementById("PubPic2").src="/image/pub?a="+d.getTime();
  // document.getElementById("CarPic2").src="/image/carpark?a="+d.getTime();

  $.get("/address", function (data){
    document.getElementById(id="address2").innerHTML=data.address;
  });


  // $.get("/test", function (data) {
  //               document.getElementById("HousePic2").title = data.residents;
  //               document.getElementById("PedPic2").title = data.ped;
  //               document.getElementById("CafePic2").title = data.cafe;
  //               document.getElementById("AccPic2").title = data.accessible;
  //               document.getElementById("GalPic2").title = data.gallery;
  //               document.getElementById("PrintPic2").title = data.prints;
  //               document.getElementById("PubPic2").title = data.pubs;

  //             });

}

// First Second clicks
var clicked = false;
    
    function countClicks()
    {
       if(clicked)
       { // Second Click
        var mapLayer2 = map.getLayer('circle2');
        if (typeof mapLayer2 !== 'undefined') {
                    // Remove map layer & source.
        map.removeLayer('circle2').removeSource('circle2');
                };
        circleMaker2(); // Right
        reloadImg2(); // Right
        updateLineChart();
        if($(window).width() >= 1600) {
          expandSecond(); // Second Click
        }

          clicked = false;
       }
      else
      { // First Click
        var mapLayer1 = map.getLayer('circle1');
        if (typeof mapLayer1 !== 'undefined') {
                    // Remove map layer & source.
        map.removeLayer('circle1').removeSource('circle1');
                };
        circleMaker1(); // Left
        reloadImg();  // Left
        updateLineChart1();
        if($(window).width() >= 1600) {
          expandFirst(); // First click
        }

         clicked = true;
      }
    }
    var clicked = false;

// Expand Page Feature
  function expandFirst() {

  $('#map').animate({
    'width': '75%'
  }, 600);

  $("#right-bg1").css({'display' : 'inline-block'});
  $('#right-bg1').animate({
    'min-width': '25%',
    'min-height': '100%'
  }, 600);
  $("#right-bg1").addClass('col-sm-3');

  $("#right-bg2").hide();

}

function expandSecond() {


  $('#map').animate({
    'width': '50%'
  }, 600);

  $('#right-bg1').animate({
    'min-width': '50%',
    'min-height': '100%'
  }, 600);

  $("#right-bg2").css('display', 'inline-block');
  $('#right-bg2').animate({
    'min-width': '25%',
    'min-height': '100%'
  }, 600);
  $("#right-bg2").addClass('col-sm-3');


}


</script>

</head>

<body>
<!-- Nav Bar -->
<div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
        </div>
    </div>

<!-- Map Box -->
<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-6 sidenav" id=map_area onclick="countClicks()">
      <nav id='menu'></nav>
      <div id='map'></div>
      <div id='geocoder' class='geocoder'></div>
      <pre id='info'></pre>

      <script>
      mapboxgl.accessToken = 'pk.eyJ1IjoicGx1c21nIiwiYSI6ImNqdGwxb3kxNjAwdmo0YW8xdjM4NG9zZWwifQ.Z9-QBnfpJDefBW7VzvC4mA';

      // Set bounds to CBD
      // var bounds = [
      //   [144.9437392829542, -37.82724080876474],  // Northeast coordinates
      //   [144.97613141562647, -37.8015691517381] // Southwest coordinates
      // ];

      var map = new mapboxgl.Map({
      container: 'map', // container id
      style: 'mapbox://styles/plusmg/cjvchk9qc0fdn1fp74xjuz92r',
      center: [144.9628079612438, -37.81370894743138], // starting position [lng, lat]
      zoom: 14.2 // starting zoom
      // maxBounds: bounds // Sets bounds as max
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
          'circle-color': 'rgba(255,224,98,1)'
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
          'circle-color': 'rgba(185,238,255,1)'
          }
          });
      });

      var url3 = {{ geojson_cafe }};

      map.on('load', function () {
          map.addSource('cafe', {
              type: 'geojson',
              data: url3
          }),

          map.addLayer({
          'id': 'cafe',
          'source': 'cafe',
          'minzoom': 16,
          'type': 'circle',
          'paint': {
          'circle-radius': 4,
          'circle-color': 'rgba(249,235,218,0.8)'
          },
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
          console.log(location)
          obj = JSON.parse(location);
          console.log(obj);

          // GEOCODING NOT WOKRING ATM

          // geocodingClient.reverseGeocode({
          // query: [obj.lng, obj.lat],
          //  })
          // .send()
          // .then(response => {
          //   // GeoJSON document with geocoding matches
          //   const match = response.body;
          //   console.log(match)
          // });
          
          $.ajax(
                  { type : 'POST',
                  url : "http://ec2-13-58-204-56.us-east-2.compute.amazonaws.com:5000/test",
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
          var clickLog = e.result.geometry.coordinates;

          $.ajax(
                  { type : 'POST',
                  url : "http://ec2-13-58-204-56.us-east-2.compute.amazonaws.com:5000/test1",
                  contentType: "application/json;charset=UTF-8",
                  dataType:'json',
                  data : JSON.stringify({'data':JSON.stringify(clickLog)})
                  }
              );
          });
      });


      // Circle on Click
      function circleMaker1(e) {
          var center = [obj.lng, obj.lat];
          var radius = 200;
          var options = {
              steps: 30,
              units: 'meters',
              properties: {
                  foo: 'bar'
              }
          }
          var circle1 = turf.circle(center, radius, options);
          map.addLayer({
              "id": "circle1",
              "type": "fill",
              "source": {
                  "type": "geojson",
                  "data": circle1
              },
              "paint": {
                  "fill-color": 'rgba(255,170,170,1)',
                  "fill-opacity": 0.3
              }
          });
      };


      function circleMaker2(e) {
          var center = [obj.lng, obj.lat];
          var radius = 200;
          var options = {
              steps: 30,
              units: 'meters',
              properties: {
                  foo: 'bar'
              }
          }
          var circle2 = turf.circle(center, radius, options);
          map.addLayer({
              "id": "circle2",
              "type": "fill",
              "source": {
                  "type": "geojson",
                  "data": circle2
              },
              "paint": {
                  "fill-color": 'rgba(255,228,196,1)',
                  "fill-opacity": 0.3
              }
          });
      };



      </script>
    </div>


    <!-- Left Side -->
    <!-- Location A -->
    <div class="col-sm-3" id="right-bg1">
      <div class="row-sm-4">
      <button type="button" id=LocButton>LOCATION A</button>
        <br>
        <h5><i style='font-size:14px' class='fas'>&#xf124;</i><b>Address: </b></h5><h5 id = address1></h5>
        <br>
        
      </div>

      <!-- Icons -->
      <div class="row-sm-4">
      <button type="button" id="LocTitle">WHATS NEARBY</button>
                 <br>
                 <br> 
          
           <!-- TOILET ICON -->
          <i id="toiletIcon" class="material-icons" style="font-size:36px">accessible</i>
          <!-- ARTWORK ICON -->
          <i id="artworkIcon" class="material-icons"  style="font-size:36px;">wallpaper</i>
          <!-- RESTAURANT ICON -->
          <i id="restaurantIcon" class="material-icons" style="font-size:36px">restaurant</i>
          <!-- PARKING ICON -->
          <i id="parkingIcon" class="material-icons" style="font-size:36px">local_parking</i>
          <!-- TRAM STOP ICON -->
          <i id="tramstopIcon" class="material-icons" style="font-size:36px">tram</i>
          <!-- OFFICEBUILDING ICON -->
          <i id="officeIcon" class="material-icons" style="font-size:36px">work</i>
         
          <!-- <img id="HousePic" title = ""> <img id="PedPic" title = ""> <img id="CafePic" title = "">  <img id="AccPic"  title = ""> <img id="GalPic"  title = ""> <img id="PrintPic"  title = ""> <img id="PubPic"  title = "">  <img id="CarPic"  title = ""> -->
        <br>
      </div>

      <!-- Chart -->
      <div class="row-sm-4">
      <button type="button" id="LocTitle">FOOT TRAFFIC FORECAST</button>
          <br>
          <canvas id="lineChart1" height="300" width=auto></canvas>
          <br>
        <script>

        const CHART1 = document.getElementById("lineChart1");
        console.log(CHART1);
        let lineChart1 = new Chart(CHART1, {
            type: 'line',
            data: {
                labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                datasets: [
                    {   data: [],
                        label: "Past 6 Months",
                        fill: false,
                        backgroundColor: "rgb(82,105,136,0.8)",
                        borderColor: "rgb(82,105,136)",
                        borderCapStyle: "butt",
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: 'white',
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 10,
                        pointHoverBackgroundColor: "rgb(82,105,136,0.8)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 8,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        lineTension: 0.4,
                    },
                    {    
                        data: [],
                        label: "This Winter",
                        fill: false,
                        backgroundColor: "rgb(255,135,120,0.5)",
                        borderColor: "rgb(255,135,120)",
                        borderCapStyle: "butt",
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "white",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 10,
                        pointHoverBackgroundColor: "rgb(255,135,120,0.5)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 8,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        lineTension: 0.4,
                    }
                ]
            },
            options: {
                responsive: false,
                maintainAspectRatio: this.maintainAspectRatio,
                title: {
                    display: true,
                    text: "Number of Pedestrian Count",
                    fontSize: 16,
                },
                legend: {
                    position: 'bottom',

                    labels: {
                        padding: 20,
                    }
                ,scale: {
                  yAxes: [{
                    beginAtZero: true
                  }]
                }
                }
            }

        });

          var updateLineChart1 = function() {
              $.get("/data", function (result) {
                console.log(result)
                lineChart1.data.datasets[0].data = result.results;
                lineChart1.data.datasets[1].data = result.results_winter;
                lineChart1.update();
            }); 
        }
        </script>
      </div>

    </div>
    
      <!-- Right Side -->
      <!-- Location B -->
      <div class="col-sm-3" id="right-bg2">
      <div class="row-sm-4">
      <button type="button" id=LocButton2>LOCATION B</button>
        <br>
        <h5><i style='font-size:14px' class='fas'>&#xf124;</i><b>Address: </b></h5><h5 id = address2></h5>
        <br>
      </div>

         <!-- Icons -->
         <div class="row-sm-4">
          <button type="button" id="LocTitle">WHATS NEARBY</button>
                 <br>
                 <br>  
          <!-- TOILET ICON -->
          <i id="toiletIcon2" class="material-icons" style="font-size:36px">accessible</i>
          <!-- ARTWORK ICON -->
          <i id="artworkIcon2" class="material-icons"  style="font-size:36px;">wallpaper</i>
          <!-- RESTAURANT ICON -->
          <i id="restaurantIcon2" class="material-icons" style="font-size:36px">restaurant</i>
          <!-- PARKING ICON -->
          <i id="parkingIcon2" class="material-icons" style="font-size:36px">local_parking</i>
          <!-- TRAM STOP ICON -->
          <i id="tramstopIcon2" class="material-icons" style="font-size:36px">tram</i>
          <!-- OFFICEBUILDING ICON -->
          <i id="officeIcon2" class="material-icons" style="font-size:36px">work</i>


          <!-- <img id="HousePic2" title = ""> <img id="PedPic2" title = ""> <img id="CafePic2" title = "">  <img id="AccPic2"  title = ""> <img id="GalPic2"  title = ""> <img id="PrintPic2"  title = ""> <img id="PubPic2"  title = "">  <img id="CarPic2"  title = ""> -->
                 <br>
        </div>
        
        <!-- Chart -->
        <button type="button" id="LocTitle">FOOT TRAFFIC FORECAST</button>
             <br>
              <canvas id="lineChart" height="300" width=auto></canvas>
              <br>

      <script>
        
        const CHART = document.getElementById("lineChart");
        console.log(CHART);
        let lineChart = new Chart(CHART, {
            type: 'line',
            data: {
                labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                datasets: [
                    {   data: [],
                        label: "Past 6 Months",
                        fill: false,
                        backgroundColor: "rgba(82,105,136,0.8)",
                        borderColor: "rgb(82,105,136)",
                        borderCapStyle: "butt",
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: 'white',
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 10,
                        pointHoverBackgroundColor: "rgba(82,105,136,0.8)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 8,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        lineTension: 0.4,
                    },
                    {    
                        data: [],
                        label: "This Winter",
                        fill: false,
                        backgroundColor: "rgba(255,135,120,0.8)",
                        borderColor: "rgb(255,135,120)",
                        borderCapStyle: "butt",
                        borderDash: [],
                        borderDashOffset: 0.0,
                        borderJoinStyle: 'miter',
                        pointBorderColor: "white",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 2,
                        pointHoverRadius: 10,
                        pointHoverBackgroundColor: "rgba(255,135,120,0.8)",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointHoverBorderWidth: 8,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        lineTension: 0.4,
                    }
                ]
            },
            options: {
                responsive: false,
                maintainAspectRatio: this.maintainAspectRatio,
                title: {
                    display: true,
                    text: "Number of Pedestrian Count",
                    fontSize: 16,
                },
                legend: {
                    position: 'bottom',

                    labels: {
                        padding: 20,
                    }
                }
                ,scale: {
                  yAxes: [{
                    beginAtZero: true
                  }]
                }
            }

        });

          var updateLineChart = function() {
              $.get("/data", function (result) {
                console.log(result)
                lineChart.data.datasets[0].data = result.click2;
                lineChart.data.datasets[1].data = result.click2_winter;
                lineChart.update();
            }); 
        }
        
        </script>
      </div>
      </div>

      <div class="row-sm-4">
        <br>  
        <br>
        <h4><small>RECENT POSTS</small></h4>
        <br>
        <h5><span class="label label-success">Lorem</span></h5><br>
        <h5>This place will be Location B</h5>
        <br>   
      </div>

      <div class="row-sm-4">
        <h4>Leave a Comment:</h4>
        <p>nisi ut aliquip ex ea commodo consequat. Excepteur sint occaecat cupidatat non proident.</p>
       </div>
      

  </div>
</div>


</body>
</html>