<x-layout.dashboardTemplate>
  <div class="container-fluid tw-my-5 | tw-px-4 max-sm:tw-px-0 ">
    <nav class="tw-flex | max-sm:justify-center" aria-label="Breadcrumb">
      <ol class="tw-inline-flex tw-items-center tw-space-x-1 | md:tw-space-x-2 rtl:tw-space-x-reverse max-sm:tw-text-sm">
        <x-partials.breadcrumb href="{{ route(Auth::user()->hasRole('Super Admin') ? 'superadmin.dashboard' : (Auth::user()->hasRole('Admin') ? 'admin.dashboard' : 'staff.dashboard')) }}" :active="false" :isLast="false">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-table-columns"></i></div>
          Dashboard
        </x-partials.breadcrumb>

        <x-partials.breadcrumb :active="true" :isLast="true">
          Map
        </x-partials.breadcrumb>
      </ol>
    </nav>

    <div class="tw-max-w-9xl tw-mx-auto tw-mt-3 tw-mb-6 tw-space-y-6">
      <div id="map" style="height: 500px"></div>
    </div>
  </div>


  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var map = L.map('map').fitWorld(); // Ensures it auto-fits

      // Add OpenStreetMap Tile Layer
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      var bounds = L.latLngBounds();

      // ** Warehouses **
      var warehouses = [{
          lat: 14.7319,
          lng: 121.0262,
          name: "Meycauayan Warehouse"
        },
        {
          lat: 14.6494,
          lng: 121.0443,
          name: "Quezon City Warehouse"
        },
        {
          lat: 14.6336,
          lng: 121.0943,
          name: "Marikina Warehouse"
        },
        {
          lat: 14.5674,
          lng: 121.0367,
          name: "Makati Warehouse"
        },
        {
          lat: 14.6539,
          lng: 120.9622,
          name: "Caloocan Warehouse"
        }
      ];

      // Define custom icons
      var startIcon = L.divIcon({
        className: 'custom-start-icon',
        html: '<i class="fas fa-map-marker-alt fa-2x" style="color: blue;"></i>',
        iconSize: [30, 30],
        iconAnchor: [15, 30]
      });

      var endIcon = L.divIcon({
        className: 'custom-end-icon',
        html: '<i class="fas fa-map-marker-alt fa-2x" style="color: red;"></i>',
        iconSize: [30, 30],
        iconAnchor: [15, 30]
      });

      @foreach($tripTickets as $ticket)
      var pickup = [{{ $ticket->pickUpLat }}, {{ $ticket->pickUpLng }}];
      var dropoff = [{{ $ticket->dropOffLat }}, {{ $ticket->dropOffLng }}];

      // Fetch route from OSRM
      fetch(`https://router.project-osrm.org/route/v1/driving/${pickup[1]},${pickup[0]};${dropoff[1]},${dropoff[0]}?overview=full&geometries=geojson`)
        .then(response => response.json())
        .then(data => {
          var routeCoordinates = data.routes[0].geometry.coordinates.map(coord => [coord[1], coord[0]]);

          // Draw route
          var route = L.polyline(routeCoordinates, {
            color: 'red',
            weight: 4,
            opacity: 0.7,
            dashArray: '5, 10'
          }).addTo(map);

          // Add Start Marker
          L.marker(pickup, {
            icon: startIcon
          }).bindPopup("Start Location ({{ $ticket->tripNumber }})").addTo(map);

          // Add End Marker
          L.marker(dropoff, {
            icon: endIcon
          }).bindPopup("End Location ({{ $ticket->tripNumber }})").addTo(map);

          bounds.extend(pickup);
          bounds.extend(dropoff);
        })
        .catch(error => console.error('Error fetching route:', error));
      @endforeach


      // Add Warehouse Markers
      warehouses.forEach(function(warehouse) {
        var marker = L.marker([warehouse.lat, warehouse.lng], {
          icon: L.divIcon({
            className: 'custom-icon',
            html: '<i class="fas fa-warehouse fa-2x" style="color: green;"></i>',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
          })
        }).bindPopup(warehouse.name).addTo(map);

        bounds.extend(marker.getLatLng());
      });

      // ** Legend with Clickable Warehouses **
      var legend = L.control({
        position: 'bottomleft'
      });

      legend.onAdd = function() {
        var div = L.DomUtil.create('div', 'legend');
        div.style.padding = "10px";
        div.style.background = "white";
        div.style.border = "1px solid #ccc";
        div.style.borderRadius = "5px";

        var warehouseLegend = document.createElement("div");
        warehouseLegend.innerHTML = '<i class="fas fa-warehouse" style="color: green; cursor: pointer;"></i> Warehouses';
        warehouseLegend.style.cursor = "pointer";
        warehouseLegend.addEventListener("click", function() {
          var randomWarehouse = warehouses[Math.floor(Math.random() * warehouses.length)];
          map.setView([randomWarehouse.lat, randomWarehouse.lng], 14);
        });

        var routeLegend = document.createElement("div");
        routeLegend.innerHTML = '<i class="fas fa-route" style="color: red;"></i> Routes';

        var startLegend = document.createElement("div");
        startLegend.innerHTML = '<i class="fas fa-map-marker-alt " style="color: blue;"></i> Start Location';

        var endLegend = document.createElement("div");
        endLegend.innerHTML = '<i class="fas fa-map-marker-alt " style="color: red;"></i> End Location';

        div.appendChild(warehouseLegend);
        div.appendChild(routeLegend);
        div.appendChild(startLegend);
        div.appendChild(endLegend);

        return div;
      };

      legend.addTo(map);
      map.fitBounds(bounds.pad(0.2));

    });
  </script>

</x-layout.dashboardTemplate>