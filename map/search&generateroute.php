<!DOCTYPE html>
<html>
<head>
    <title>Leaflet.js Map with Navigation Bar</title>
    <!-- Include Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <style>
        body {
            background: #1d2026;
            #map {
            height: 590px;
        }

        #navbar {
            background-color: #1d2630;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 35px;
        }

        #navbar input[type="text"] {
            text-align: center;
            color: rgb(8, 8, 8);
            width: 300px;
            padding: 5px;
            margin-right: 5px;
            border-radius: 17px;
        }

        #navbar button {
            text-align: right;
            color: white;
            padding: 5px 10px;
            margin-right: 5px;
            border-radius: 17px;
            display: inline-block;
            list-style: none;
            margin: 0 5px;
            color: black;
            font-style: none;
        }

        #navbar img {
            height: 45px;
        }
    </style>
</head>
<body>
    <div id="navbar">
        <img id="logo" src="resources/images/xmain-logo.png.pagespeed.ic.6k4m9rysxz.webp"></img><br><br> 
        <input type="text" id="searchInput" placeholder="Search marker name">
        <button id="searchButton">Search</button>
        <button id="routeButton">Generate Route</button>
        <button id="refreshButton">Refresh</button>
        <a href="login.php"><button id="signInButton">Sign In</button></a>
    </div>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
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
        map.setMaxBounds(bounds);
        map.setMinZoom(15); // Set minimum zoom level to enforce maxZoom

        // Disable zooming beyond the maximum zoom level
        map.on('zoomend', function() {
            if (map.getZoom() > maxZoom) {
                map.setZoom(maxZoom);
                        }
        });

        // Set map boundaries
        var southWest = L.latLng(-19.52065024347446, 29.828591390863437);
        var northEast = L.latLng(-19.509150167047967, 29.84508134676824);
        var bounds = L.latLngBounds(southWest, northEast);

         // Set map boundaries
         map.setMaxBounds(bounds);
        map.on('drag', function() {
            map.panInsideBounds(bounds, { animate: false });
        });

        // Marker data as an array
        var markers = [
            {
                name: 'Main Library',
                description: 'This is the Main Library',
                lat: -19.516477813045007,
                lng: 29.83997312777198
            },
            {
                name: 'Great Hall',
                description: 'This is the Great Hall',
                lat: -19.51590437990416,
                lng: 29.839715016462762
            },
            {
                name: 'Lab 6',
                description: 'This is a lab for Biomedical Engineering Students',
                lat: -19.51553642634793,
                lng: 29.839120869398904
            },
            {
                name: 'Rufaro Hostel',
                description: 'This is Rufaro Boys Hostel',
                lat: -19.514960513481412,
                lng: 29.84021092155859
            },
            {
                name: 'Japan Dining',
                description: 'This is the Dining for Japan boys hostels',
                lat: -19.512344305320482, 
                lng: 29.831684020866806
            },
            {
                name: 'Main Dining',
                description: 'The is the Main Campus main dining. Open for breakfast, 0800-1000 for lunch, 1200-1430 and for dinner, 1600-1800',
                lat: -19.517543269985786, 
                lng: 29.83922220434599
            },
            {
                name: 'Wadzanai Girls Hostel',
                description: 'This is a girl`s hostel, no boys allowed',
                lat: -19.51549060853578, 
                lng: 29.840171204986632
            },
            {
                name: 'Swimming Pool',
                description: 'This is the main campus swimming pool, it is currently out of commission',
                lat: -19.518290589473786, 
                lng: 29.839155678791524
            },
            {
                name: 'Computer Lab 1',
                description: 'This is a Computer lab for HCSCI and HCSE students. Open on weekdays from 0800 to 1600',
                lat: -19.51624696943058, 
                lng: 29.838761387542895
            },
            {
                name: 'Main Car Park',
                description: 'This is the main car park for the University',
                lat: -19.51754050790593, 
                lng: 29.836554391159197
            },
            {
                name: 'Chapel',
                description: 'This is the main Chapel',
                lat: -19.513558415788722,
                lng: 29.839184747466884
            },
            // Add more markers here as needed
        ];

        // Loop through the markers array and add markers to the map
        var mapMarkers = [];
        markers.forEach(function(marker) {
            var markerPopupContent = '<h3>' + marker.name + '</h3><p>' + marker.description + '</p>';

            var mapMarker = L.marker([marker.lat, marker.lng]).addTo(map);
            mapMarker.bindPopup(markerPopupContent);
            mapMarkers.push(mapMarker);

            // Hide marker icon and popup by default
    //markerObject.setOpacity(0);
    //markerObject.closePopup();

    // Show marker icon and popup on hover
    //markerObject.on('mouseover', function (e) {
        //var hoveredMarker = e.target;
        //hoveredMarker.setOpacity(1);
        //hoveredMarker.openPopup();
    //});

    // Hide marker icon and popup on mouseout
    //markerObject.on('mouseout', function (e) {
        //var hoveredMarker = e.target;
        //hoveredMarker.setOpacity(0);
        //hoveredMarker.closePopup();
        });

        // Search button click event handler
        var searchButton = document.getElementById('searchButton');
        searchButton.addEventListener('click', function() {
            var searchInput = document.getElementById('searchInput').value;
            var matchedMarker = markers.find(function(marker) {
                return marker.name.toLowerCase() === searchInput.toLowerCase();
            });

            if (matchedMarker) {
                var matchedMapMarker = mapMarkers.find(function(mapMarker) {
                    return mapMarker.getLatLng().equals([matchedMarker.lat, matchedMarker.lng]);
                });

                if (matchedMapMarker) {
                    map.panTo(matchedMapMarker.getLatLng());
                    matchedMapMarker.openPopup();
                }

                // Set the second waypoint for route generation
                var routeControl = L.Routing.control({
                    waypoints: [
                        L.latLng(-19.516312310001947, 29.83897327199999),
                        L.latLng(matchedMarker.lat, matchedMarker.lng)
                    ]
                }).addTo(map);

                routeControl.hide(); // Hide the route initially
            } else {
                alert('Invalid Location Search');
            }
        });

       // Route button click event handler
var routeButton = document.getElementById('routeButton');
routeButton.addEventListener('click', function() {
    var routeControl = L.Routing.control({
        routeWhileDragging: true,
        show: false, // Hide the route initially
        createMarker: function() { return null; } // Disable default markers
    });

    if (routeControl.getWaypoints()[1]) {
        routeControl.addTo(map);

        // Create a custom marker icon for the source destination
        var sourceIcon = L.divIcon({
            className: 'start-marker-icon'
        });

        // Set the custom icon for the source destination marker
        routeControl.getWaypoints()[0].marker.setIcon(sourceIcon);

        // Provide a container element for the directions
        var directionsContainer = document.createElement('div');
        directionsContainer.id = 'directions-container';
        document.body.appendChild(directionsContainer);

        // Display the directions in the container element
        //routeControl.on('routesfound', function(e) {
            //var routes = e.routes;
            //var summary = routes[0].summary;
            //var instructions = routes[0].instructions;

            //var directionsContent = '<h3>Route Summary</h3>';
            //directionsContent += '<p>Distance: ' + summary.totalDistance + '</p>';
            //directionsContent += '<p>Duration: ' + summary.totalTime + '</p>';
            //directionsContent += '<h3>Directions</h3>';
            //directionsContent += '<ol>';
            //instructions.forEach(function(instruction) {
                //directionsContent += '<li>' + instruction.text + '</li>';
            //});
            //directionsContent += '</ol>';

            //directionsContainer.innerHTML = directionsContent;
        //});
    } else {
        alert('No location searched for');
    }
});

       // Route button click event handler
var routeButton = document.getElementById('routeButton');
routeButton.addEventListener('click', function() {
    var routeControl = L.Routing.control({
        routeWhileDragging: true,
        show: false, // Hide the route initially
        createMarker: function() { return null; } // Disable default markers
    });

    if (routeControl.getWaypoints()[1]) {
        routeControl.addTo(map);

        // Create a custom marker icon for the source destination
        var sourceIcon = L.divIcon({
            className: 'start-marker-icon'
        });

        // Set the custom icon for the source destination marker
        routeControl.getWaypoints()[0].marker.setIcon(sourceIcon);

        // Provide a container element for the directions
        var directionsContainer = document.createElement('div');
        directionsContainer.id = 'directions-container';
        document.body.appendChild(directionsContainer);

        // Display the directions in the container element
        routeControl.on('routesfound', function(e) {
            var routes = e.routes;
            var summary = routes[0].summary;
            var instructions = routes[0].instructions;

            var directionsContent = '<h3>Route Summary</h3>';
            directionsContent += '<p>Distance: ' + summary.totalDistance + '</p>';
            directionsContent += '<p>Duration: ' + summary.totalTime + '</p>';
            directionsContent += '<h3>Directions</h3>';
            directionsContent += '<ol>';
            instructions.forEach(function(instruction) {
                directionsContent += '<li>' + instruction.text + '</li>';
            });
            directionsContent += '</ol>';

            directionsContainer.innerHTML = directionsContent;
        });
    } else {
        alert('No location searched for');
    }
});

// Add click event listener to the refresh button
refreshButton.addEventListener('click', function () {
            location.reload(); // Reload the page
        });
    </script>

    </body>
</html>