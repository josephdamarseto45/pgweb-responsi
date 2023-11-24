<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    />
    <!-- Marker Cluster -->
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-markercluster/MarkerCluster.css"
    />
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-markercluster/MarkerCluster.Default.css"
    />
    <!-- Routing -->
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-routing/leaflet-routing-machine.css"
    />
    <!-- Search CSS Library -->
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-search/leaflet-search.css"
    />
    <!-- Geolocation CSS Library for Plugin -->
    <link
      rel="stylesheet"
      href="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.css"
    />
    <!-- Leaflet Mouse Position CSS Library -->
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-mouseposition/L.Control.MousePosition.css"
    />
    <!-- Leaflet Measure CSS Library -->
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-measure/leaflet-measure.css"
    />
    <!-- EasyPrint CSS Library -->
    <link
      rel="stylesheet"
      href="assets/plugins/leaflet-easyprint/easyPrint.css"
    />
    <style>
    html, body, #map {
        height: 100%;
        width: 100%;
        margin: 0px;
    }
    /* Background pada Judul */
    *.info {
        padding: 6px 8px;
        font: 14px/16px Arial, Helvetica, sans-serif;
        background: white;
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        text-align: center;
    }

    .info h2 {
        margin: 0 0 5px;
        color: #777;
    
        }
    </style>
</head>

<body>
<div id="map"></div>

<!-- Include your GeoJSON data -->
<script src="./data.js"></script>

<!-- Leaflet and Plugins -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="assets/plugins/leaflet-markercluster/leaflet.markercluster.js"></script>
<script src="assets/plugins/leaflet-markercluster/leaflet.markercluster-src.js"></script>
<script src="assets/plugins/leaflet-routing/leaflet-routing-machine.js"></script>
<script src="assets/plugins/leaflet-routing/leaflet-routing-machine.min.js"></script>
<script src="assets/plugins/leaflet-search/leaflet-search.js"></script>
<script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.min.js"></script>
<script src="assets/plugins/leaflet-mouseposition/L.Control.MousePosition.js"></script>
<script src="assets/plugins/leaflet-measure/leaflet-measure.js"></script>
<script src="assets/plugins/leaflet-easyprint/leaflet.easyPrint.js"></script>

<script>
  // Initialize the map
  var map = L.map("map").setView([-6.283818, 106.804863], 10);

  // Basemaps
  var basemap1 = L.tileLayer(
    "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
    {
      maxZoom: 19,
      attribution:
        'Map data © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }
  );
  basemap1.addTo(map);

  // Point 
  
  // Create a GeoJSON layer for polygon data
  var geoservermap = L.geoJson(null, {
    style: function (feature) {
      // Adjust this function to define styles based on your polygon properties
      var value = feature.properties.jmlhfaspen; // Change this to your actual property name
      return {
        fillColor: getColor(value),
        weight: 2,
        opacity: 1,
        color: "white",
        dashArray: "3",
        fillOpacity: 0.7,
      };
    },
    onEachFeature: function (feature, layer) {
// Adjust the popup content based on your polygon properties
var content =
"Kecamatan: " +
feature.properties.namobj +
"<br>" +
"SD: " +
feature.properties.sd +
"<br>" +
"SMP: " +
feature.properties.smp +
"<br>" +
"SMA: " +
feature.properties.sma +
"<br>" +
"Perguruan Tinggi: " +
feature.properties.pt +
"<br>" +
"Jumlah Fasilitas Pendidikan: " +
feature.properties.jmlhfaspen +
"<br>";;
layer.bindPopup(content, { maxWidth: '500' });
},
  });

  
  // Fetch GeoJSON data from geoserver.php
  $.getJSON("geoservermap.php", function (data) {
    geoservermap.addData(data);
    geoservermap.addTo(map);
    map.fitBounds(geoservermap.getBounds());
  });

  // Array of markers
  var markersArray = [
    {
      coordinates: [-6.255411008595897, 106.8107879769063],
      options: { draggable: true },
      popupContent: "Gedung B DIVSIG UGM",
    },
    {
      coordinates: [-6.255411008595897, 106.8107879769063],
      options: {},
      popupContent: "RS.Akademik UGM",
    },
  ];

 

  // Title
  var title = new L.Control();
  title.onAdd = function (map) {
    this._div = L.DomUtil.create("div", "info");
    this.update();
    return this._div;
  };
  title.update = function () {
    this._div.innerHTML =
      "<h2>WEBGIS - SI.PENJAKS - PETA FASILITAS PENDIDIKAN JAKARTA SELATAN</h2>PEMROGRAMAN GEOSPASIAL : WEB - JOSEPH DAMARSETO";
  };
  title.addTo(map);

  // Watermark 
  L.Control.Watermark = L.Control.extend({
    onAdd: function (map) {
      var container = L.DomUtil.create("div", "leaflet-control-watermark");
      var img = L.DomUtil.create("img", "watermark-image");
      img.src = "assets/img/logo/engineer.png";
      img.style.width = "120px";
      container.appendChild(img);
      return container;
    },
  });
  L.control.watermark = function (opts) {
    return new L.Control.Watermark(opts);
  };

  L.control.watermark({ position: "bottomleft" }).addTo(map);
  
  // Legend
  L.Control.Legend = L.Control.extend({
    onAdd: function (map) {
      var img = L.DomUtil.create("img");
      img.src = "assets/img/legend/Presentation1.jpg";
      img.style.width = "300px";
      return img;
    },
  });
  L.control.Legend = function (opts) {
    return new L.Control.Legend(opts);
  };

  L.control.Legend({ position: "bottomleft" }).addTo(map);
  // Basemaps
  var basemap1 = L.tileLayer(
    "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
    {
      maxZoom: 19,
      attribution:
        'Map data © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }
  );

  var basemap2 = L.tileLayer(
    "https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}",
    {
      attribution:
        'Tiles &copy; Esri | <a href="DIVSIGUGM" target="_blank">DIVSIG UGM</a>',
    }
  );

  var basemap3 = L.tileLayer(
    "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
    {
      attribution:
        'Tiles &copy; Esri | <a href="Lathan WebGIS" target="_blank">DIVSIG UGM</a>',
    }
  );

  var basemap4 = L.tileLayer(
    "https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png",
    {
      attribution:
        '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
    }
  );

  basemap3.addTo(map);

  var baseMaps = {
    OpenStreetMap: basemap1,
    "Esri World Street": basemap2,
    "Esri Imagery": basemap3,
    "Stadia Dark Mode": basemap4,
  };

  L.control.layers(baseMaps).addTo(map);

  // Plugin Search
  var searchControl = new L.Control.Search({
    position: "topleft",
    layer: geoservermap, // Nama variabel layer
    propertyName: "kecamatan", // Field untuk pencarian
    marker: false,
    moveToLocation: function (latlng, title, map) {
      var zoom = map.getBoundsZoom(latlng.layer.getBounds());
      map.setView(latlng, zoom);
    },
  });

  searchControl
    .on("search:locationfound", function (e) {
      e.layer.setStyle({
        fillColor: "#ffff00",
        color: "#0000ff",
      });
    })
    .on("search:collapse", function (e) {
        geoservermap.eachLayer(function (layer) {
        geoservermap.resetStyle(layer);
      });
    });

  map.addControl(searchControl);

  // Plugin Geolocation
  var locateControl = L.control
    .locate({
      position: "topleft",
      drawCircle: true,
      follow: true,
      setView: true,
      keepCurrentZoomLevel: false,
      markerStyle: {
        weight: 1,
        opacity: 0.8,
        fillOpacity: 0.8,
      },
      circleStyle: {
        weight: 1,
        clickable: false,
      },
      icon: "fas fa-crosshairs",
      metric: true,
      strings: {
        title: "Click for Your Location",
        popup: "You're here. Accuracy {distance} {unit}",
        outsideMapBoundsMsg: "Not available",
      },
      locateOptions: {
        maxZoom: 16,
        watch: true,
        enableHighAccuracy: true,
        maximumAge: 10000,
        timeout: 10000,
      },
    })
    .addTo(map);

  // Plugin Mouse Position Coordinate
  L.control
    .mousePosition({
      position: "bottomright",
      separator: ",",
      prefix: "Point Coodinate: ",
    })
    .addTo(map);

  // Plugin Measurement Tool
  var measureControl = new L.Control.Measure({
    position: "topleft",
    primaryLengthUnit: "meters",
    secondaryLengthUnit: "kilometers",
    primaryAreaUnit: "hectares",
    secondaryAreaUnit: "sqmeters",
    activeColor: "#FF0000",
    completedColor: "#00FF00",
  });

  measureControl.addTo(map);

  // Plugin EasyPrint
  L.easyPrint({
    title: "Print",
  }).addTo(map);

  // Plugin Routing
  L.Routing.control({
    waypoints: [
      L.latLng(-6.283818, 106.804863),
      L.latLng(-6.283818, 106.804863),
    ],
    routeWhileDragging: true,
  }).addTo(map);

  // Layer Marker
  var addressPoints = [
   /*[-6.2555903710460505, 106.8122856022436, "SD New Zealand Independent School"],
    [-6.24878860000132, 106.82983255760436, "SDN Tegal Parang 01"],
    [-6.247805021759297, 106.81288843790665, "Sekolah Dasar Kupu - Kupu"],
    [-6.256580248551017, 106.81453819898279, "SDS Kembang"],
    [-6.25325863873464, 106.81832256674214, "SDS Delima"],

    [-6.250859984424223, 106.82466033875741, "SMP Negeri 104"],
    [-6.24884255888574, 106.82217923780149, "SMP Negeri 141"],
    [-6.244144537443291, 106.82153401092012, "Smp Dharma Satria"],
    [-6.240006386314908, 106.82642024505223, "SMPN 43"],

    [-6.247698012385869, 106.81220309742685, "SMA GARUDA CENDEKIA"],
    [-6.261880428079171, 106.81305542303515, "SMA Pelita Harapan Kemang Village"],
    [-6.249760457406597, 106.83500359557779, "SMAS 28 OKTOBER 1928"],
    [-6.2541972608002405, 106.81088575319471, "SMAS AL AZHAR SYIFA BUDI"],

    [-6.2611993234053775, 106.81685409557782, "Indonesia Banking School STIE (IBS)"],
    [-6.239234593749033, 106.81771176674211, "Sekolah Tinggi Desain Interstudi"],
    
    [-6.245157198246215, 106.79166589557772, "SMA LABSCHOOL KEBAYORAN"],
    [-6.259350025817789, 106.7883801083564, "SMA D Royal Moroco Integrative Islamic School	"],
    [-6.248110228634619, 106.79097336674221, "Sma Muhammadiyah 3 Jakarta"],
    [-6.245769972521596, 106.81221276674206, "SMA TARAKANITA 1 JAKARTA"],
    [-6.25885689705392, 106.7921422405344, "SMA Gita Kirtti 3"],
    [-6.254020493734447, 106.80638372441342, "SMU Pangudi Luhur Brawijaya"],
    [-6.247213958865347, 106.80124226859122, "SMA 4 PSKD"],
    [-6.241137698004668, 106.80588653790652, "SMA Purnama"],
    [-6.260371388296246, 106.76370066674231, "SMA Triguna Jakarta"],

    [-6.240281659013333, 106.78964899557768, "SMPN 11 JAKARTA"],
    [-6.249740457205481, 106.80346744887619, "SMP Negeri 12 Jakarta"],
    [-6.246277577180542, 106.7947667360575, "SMP Tarakanita 5"],
    [-6.263679293724922, 106.80981606674223, "SMPN 250"],
    [-6.239836793748444, 106.78955830907094, "SMP Negeri 19 Jakarta"],

    
    [-6.24768597045966, 106.79104934449408, "Universitas Muhammadiyah Prof. Dr. HAMKA"],
    [-6.2357525509278995, 106.80298581983278, "Universitas Bhayangkara"],
    [-6.245101102744458, 106.80708212441334, "Sekolah Tinggi Ilmu Kepolisian (STIK) PTIK"],
    [-6.253226220446612, 106.79793844628138, "Universitas Bhayangkara Jaya (Ubhara Jaya)"],
    [-6.234792859130449, 106.7989840379065, "University of Al Azhar Indonesia"],
    [-6.258200074631754, 106.79223221038667, "STMIK Jakarta STI&K"],
    [-6.252725658747572, 106.79838169557779, "STIKOM InterStudi"],
    [-6.241626832097744, 106.7888989630977, "Sahid Polytechnic"], */
  ];

  var markers = L.markerClusterGroup();



  for (var i = 0; i < addressPoints.length; i++) {
    var a = addressPoints[i];
    var title1 = a[2];
    var marker = L.marker(new L.LatLng(a[0], a[1]), { title: title1 });

    marker.bindPopup(title1);
    markers.addLayer(marker);
  }

  

  var markers = L.markerClusterGroup();



  for (var i = 0; i < addressPoints.length; i++) {
    var a = addressPoints[i];
    var title1 = a[2];
    var marker = L.marker(new L.LatLng(a[0], a[1]), { title: title1 });

    marker.bindPopup(title1);
    markers.addLayer(marker);
  }

  map.addLayer(markers);
  //Function to determine the color based on the 'value' attribute
  /*function getColor(value) {
    return value < 16
? "#02367b"    // 
: value >= 20 && value <= 33
? "#0496c7"  // 
: value > 34
? "#55e2e9" // */

function getColor(value) {
        return value == "23"
          ? "#ff6666"
          : value == "28"
          ? "#440381"
          : value == "24"
          ? "#66ff66"
          : value == "19"
          ? "#FFD6C0"
          : value == "20"
          ? "#cc66ff"
          : value == "25"
          ? "#ff66cc"
          : value == "15"
          ? "#66ccff"
          : value == "19"
          ? "#ccff66"
          : value == "34"
          ? "#ff9966"
          : value == "Tebet"
          ? "#9966ff"
          : value == "Jagakarsa"
       }

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "admin";
        $dbname = "responsipgweb";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "SELECT * FROM pendidikan";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $lat = $row["latitude"];
                $long = $row["longitude"];
                $info = $row["kecamatan"];
                $info = $row["nama_pendidikan"];
                
                echo "L.marker([$lat, $long]).addTo(map).bindPopup('$info', {maxWidth: 300});";
            } 
        }
        else {
            echo "0 results";
        }
            $conn->close();

?> 

</script>
</body>
</html>