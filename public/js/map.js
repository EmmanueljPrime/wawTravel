
    // const apiKey = "nlIBhColFtneuc2OQvzF"; // Remplace avec ta clé MapTiler

    document.addEventListener("DOMContentLoaded", function () {
        const apiKey = "nlIBhColFtneuc2OQvzF"; // Remplace avec ta clé API MapTiler

        const map = new maplibregl.Map({
            container: 'map',
            style: `https://api.maptiler.com/maps/streets/style.json?key=${apiKey}`,
            center: [2.3522, 48.8566],
            zoom: 5
        });

        map.addControl(new maplibregl.NavigationControl());

        // Attendre que le style soit complètement chargé avant d'ajouter les marqueurs et tracés
        map.on('styledata', function () {
            console.log("Style chargé, ajout des données...");

            fetch('/profile')
                .then(response => response.json())
                .then(data => {
                    console.log("Checkpoints chargés :", data);

                    data.forEach(trip => {
                        let latlngs = [];

                        trip.checkpoints.forEach(checkpoint => {
                            // Ajouter un marqueur pour chaque checkpoint
                            new maplibregl.Marker()
                                .setLngLat([checkpoint.longitude, checkpoint.latitude])
                                .setPopup(new maplibregl.Popup().setText(`${trip.roadTripTitle}: ${checkpoint.name}`))
                                .addTo(map);

                            latlngs.push([checkpoint.longitude, checkpoint.latitude]);
                        });

                        // Ajouter une ligne seulement si plusieurs checkpoints existent
                        if (latlngs.length > 1) {
                            map.addLayer({
                                'id': 'route-' + trip.roadTripTitle,
                                'type': 'line',
                                'source': {
                                    'type': 'geojson',
                                    'data': {
                                        'type': 'Feature',
                                        'geometry': {
                                            'type': 'LineString',
                                            'coordinates': latlngs
                                        }
                                    }
                                },
                                'layout': {
                                    'line-join': 'round',
                                    'line-cap': 'round'
                                },
                                'paint': {
                                    'line-color': '#007bff',
                                    'line-width': 3
                                }
                            });
                        }
                    });

                    // Ajuster la carte pour englober tous les points
                    if (data.length > 0) {
                        const bounds = new maplibregl.LngLatBounds();
                        data.forEach(trip => {
                            trip.checkpoints.forEach(cp => bounds.extend([cp.longitude, cp.latitude]));
                        });
                        map.fitBounds(bounds, { padding: 50 });
                    }
                })
                .catch(error => console.error('Erreur lors du chargement des checkpoints:', error));
        });
    });
