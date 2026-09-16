<?php

require_once __DIR__ . '/../navbar.php';
?>

<style>
    #donorMap { height: 620px; border-radius: 10px; width: 100%; }
</style>

<div class="card shadow-sm border-0 mt-2 p-3">
    <h3 class="text-danger fw-bold text-center mb-1">🗺️ Live Blood Network Map</h3>
    <p class="text-muted text-center small mb-3">
        <span class="me-3">🔴 Available donors</span>
        <span class="me-3">🟠 Resting (cooldown)</span>
        <span class="me-3">⚫ Unavailable</span>
        <span>🟡 Active blood requests</span>
    </p>
    <div id="donorMap"></div>
</div>

<script>
    const DONORS   = <?= json_encode($donors   ?? []) ?>;
    const REQUESTS = <?= json_encode($requests ?? []) ?>;

    function initMap() {
        const map = new google.maps.Map(document.getElementById('donorMap'), {
            center: { lat: 23.8103, lng: 90.4125 },   // Bangladesh centre
            zoom: 7,
            mapTypeControl: false,
            streetViewControl: false,
        });

        function circleIcon(fillColor) {
            return {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 11,
                fillColor: fillColor,
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 2,
            };
        }

        const COLOR = {
            Available:   '#dc3545',  // red
            Resting:     '#fd7e14',  // orange
            Unavailable: '#6c757d',  // grey
            Request:     '#ffc107',  // gold
        };

        // One shared InfoWindow — reused for every marker
        const infoWindow = new google.maps.InfoWindow();

        DONORS.forEach(function(donor) {
            const lat = parseFloat(donor.latitude);
            const lng = parseFloat(donor.longitude);
            if (!lat || !lng) return;

            const status = donor.status || 'Available';
            const color  = COLOR[status] || COLOR.Available;

            const statusLabel = status === 'Available'
                ? '<span style="color:#198754;font-weight:bold;">✅ Available</span>'
                : status === 'Resting'
                ? '<span style="color:#fd7e14;font-weight:bold;">⏳ Resting (Cooldown)</span>'
                : '<span style="color:#6c757d;font-weight:bold;">❌ Unavailable</span>';

            const marker = new google.maps.Marker({
                position: { lat, lng },
                map,
                icon:  circleIcon(color),
                title: donor.name + ' — ' + (donor.blood_group || '') + ' — ' + status,
            });

            marker.addListener('click', function() {
                infoWindow.setContent(
                    '<div style="min-width:160px;text-align:center;font-family:sans-serif;">' +
                    '<h6 style="margin:0 0 6px;font-weight:700;">' + (donor.name || 'Donor') + '</h6>' +
                    '<span style="background:#dc3545;color:#fff;padding:3px 10px;border-radius:20px;font-weight:bold;font-size:14px;">' +
                        (donor.blood_group || '') + '</span>' +
                    '<p style="margin:6px 0 2px;font-size:12px;color:#666;">' + (donor.district || donor.full_address || '') + '</p>' +
                    '<div style="margin-top:4px;">' + statusLabel + '</div>' +
                    '</div>'
                );
                infoWindow.open(map, marker);
            });
        });

        REQUESTS.forEach(function(req) {
            const lat = parseFloat(req.latitude);
            const lng = parseFloat(req.longitude);
            if (!lat || !lng || req.status !== 'Active') return;

            const marker = new google.maps.Marker({
                position: { lat, lng },
                map,
                icon:  circleIcon(COLOR.Request),
                title: req.blood_group + ' needed — ' + req.hospital_name,
            });

            const urgencyColor = req.urgency_level === 'Emergency_SOS' ? '#dc3545'
                               : req.urgency_level === 'High'          ? '#fd7e14'
                               :                                          '#6c757d';

            marker.addListener('click', function() {
                infoWindow.setContent(
                    '<div style="min-width:170px;text-align:center;font-family:sans-serif;">' +
                    '<strong style="color:#dc3545;font-size:15px;">' + (req.blood_group || '') + ' Blood Needed</strong><br>' +
                    '<span style="font-size:12px;">' + (req.hospital_name || '') + '</span><br>' +
                    '<span style="font-size:11px;color:#666;">' + (req.location || '') + '</span><br>' +
                    '<span style="background:' + urgencyColor + ';color:#fff;padding:2px 8px;border-radius:4px;font-size:11px;display:inline-block;margin-top:4px;">' +
                        (req.urgency_level || '') + '</span>' +
                    '</div>'
                );
                infoWindow.open(map, marker);
            });
        });
    }
</script>

<!-- Google Maps JS API — key comes from MapController via Config -->
<script async
    src="https://maps.googleapis.com/maps/api/js?key=<?= htmlspecialchars($gmapsApiKey ?? '') ?>&callback=initMap">
</script>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
