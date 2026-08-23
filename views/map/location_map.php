<?php
require_once __DIR__ . '/../../config/Config.php';
require_once __DIR__ . '/../../controllers/MapController.php';

$mapCtrl = new MapController();
$mapMarkersJson = $mapCtrl->getMapDataJson();
$googleMapsApiKey = defined('GOOGLE_MAPS_API_KEY') ? GOOGLE_MAPS_API_KEY : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Integrated Live Donor & Facility Map - Smart Blood Network</title>
    <style>
        #map { height: 550px; width: 100%; margin-top: 15px; border: 2px solid #2c3e50; border-radius: 5px; }
        .legend { padding: 10px; background: white; border: 1px solid #ccc; margin-bottom: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>

    <h2>Integrated Live Map: Donors, Hospitals and Blood Banks</h2>
    
    <div class="legend">
        <strong>Map Pins Legend:</strong>
        <span style="color: red; font-weight: bold; margin-left: 15px;">Red Marker: Available Donor</span>
        <span style="color: gray; font-weight: bold; margin-left: 15px;">Gray Marker: Resting / Unavailable Donor</span>
        <span style="color: blue; font-weight: bold; margin-left: 15px;">Blue Marker: Hospital / Blood Bank</span>
    </div>

    <div id="map"></div>

    <script>
        function escapeHtml(text) {
            if (!text) return '';
            return String(text).replace(/[&<>"']/g, function(m) {
                return {'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;'}[m];
            });
        }

        function initMap() {
            var defaultCenter = { lat: 23.8103, lng: 90.4125 };
            var map = new google.maps.Map(document.getElementById('map'), { zoom: 12, center: defaultCenter });

            var markers = <?= $mapMarkersJson ?>;
            var infoWindow = new google.maps.InfoWindow();

            markers.forEach(function(place) {
                var position = { lat: parseFloat(place.latitude), lng: parseFloat(place.longitude) };
                var markerIcon = (place.category === 'donor') 
                    ? (place.status === 'Available' ? 'https://maps.google.com/mapfiles/ms/icons/red-dot.png' : 'https://maps.google.com/mapfiles/ms/icons/grey-dot.png')
                    : 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png';

                var marker = new google.maps.Marker({ position: position, map: map, title: place.name, icon: markerIcon });

                var popupContent = "<div style='font-family:Arial;'>" +
                    "<strong style='font-size:14px; color:#c0392b;'>" + escapeHtml(place.name) + "</strong><br>" +
                    "<b>Type:</b> " + escapeHtml(place.type) + "<br>";

                if (place.category === 'donor') {
                    popupContent += "<b>Blood Group:</b> <span style='background:#e74c3c; color:white; padding:1px 5px; border-radius:3px;'>" + escapeHtml(place.blood_group) + "</span><br>" +
                        "<b>Status:</b> " + escapeHtml(place.status) + "<br>";
                }

                popupContent += "<b>Location:</b> " + escapeHtml(place.location) + "<br>" +
                    "<b>Contact:</b> " + escapeHtml(place.contact) + "</div>";

                marker.addListener('click', function() {
                    infoWindow.setContent(popupContent);
                    infoWindow.open(map, marker);
                });
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= urlencode($googleMapsApiKey) ?>&callback=initMap" async defer></script>
</body>
</html>