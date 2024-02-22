<!DOCTYPE html>
<html>
<head>
    <title>Leaflet.js Map with MySQL Markers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css"/>
    <style>
       body {
            background: #1d2026;
        }

        #map {
            height: 590px;
        }

        .navbar {
            background-color: #fafafc;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 30px;
        }

        .navbar input[type="text"] {
            text-align: center;
            color: rgb(8, 8, 8);
            width: 800px;
            padding: 5px;
            margin-right: 5px;
            border-radius: 17px;
        }

        .navbar button {
            text-align: right;
            padding: 5px 10px;
            margin-right: 5px;
            display: inline-block;
            list-style: none;
            margin: 0 5px;
            color: black;
            font-style: none;
        }

        .navbar button.special {
            background-color: #1d2630;
            color: white;
        }

        .navbar-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }
</style>
    </style>
</head>
<body>
<div class="navbar navbar-center">
    <button class="btn btn-success special">Midlands State University Interactive Map</button>
    <a href="search&generateroute.php"><button class="btn btn-success">Search Map Locations & Routes</button></a>
    <a href="about.php"><button class="btn btn-success">About</button></a>
    <a href="login.php"><button class="btn btn-success">Log In</button></a>
</div>
<div id="map"></div>

<script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
   // Create the map
   var map = L.map('map').setView([-19.51591677703458, 29.839758379110382], 16);

   // Add the Google Satellite layer
   L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
       maxZoom: 20,
       subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
   }).addTo(map);

   // Set maximum zoom level
   var maxZoom = 20; 

   // Set map boundaries and maximum zoom level
   var southWest = L.latLng(-19.52065024347446, 29.828591390863437);
   var northEast = L.latLng(-19.509150167047967, 29.84508134676824);
   var bounds = L.latLngBounds(southWest, northEast);
   map.setMaxBounds(bounds);
   map.setMinZoom(15);

   // Disable zooming beyond the maximum zoom level
   map.on('zoomend', function() {
       if (map.getZoom() > maxZoom) {
           map.setZoom(maxZoom);
       }
   });

   // Set map boundaries
   map.on('drag', function() {
       map.panInsideBounds(bounds, { animate: false });
   });
   // Create a custom icon
var customIcon = L.icon({
    iconUrl: "resources/images/here.png", // Replace with the path to your custom marker image
    iconSize: [30, 30], // Replace with the desired size of the marker image
    iconAnchor: [15, 30] // Replace with the anchor point of the marker image (half of the iconSize)
});

   // Connect to the database
   <?php
   $host = "localhost";
   $dbusername = "root";
   $dbpassword = "";
   $dbname = "markersdb";

   $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   // Fetch markers from the database
   $sql = "SELECT * FROM markerstb";
   $result = $conn->query($sql);

   // Add markers to the map
while ($row = $result->fetch_assoc()) {
    $name = $row['name'];
    $description = $row['description'];
    $latitude = $row['latitude'];
    $longitude = $row['longitude'];

    echo "var marker = L.marker([$latitude, $longitude], {icon: customIcon}).addTo(map);";
    echo "marker.bindPopup('<b>$name</b><br>$description');";

    
}


   $conn->close();
   ?>
</script>
</body>
</html>
