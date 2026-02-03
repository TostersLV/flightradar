<x-layout>
    <div id="map" class="relative w-full h-screen z-10">
        
        
        <div id="plane-info" class="absolute hidden  z-40 bottom-0 rounded-lg w-full h-60 md:w-1/4 md:rounded-lg md:top-0 md:left-0 md:ml-5 md:mt-5">
            <div class="bg-gray-600 rounded-lg ">
                <div class="flex p-2 ml-2 items-stretch rounded-t-lg ">
                    <p id="plane-Id" class="text-yellow-300 mr-3 font-sans font-semibold text-lg"></p>
                    <div class="border-none rounded-lg bg-sky-800 mr-2">
                        <p id="plane-callSign" class="p-1 font-semibold text-white"></p>
                    </div>
                    <div class="border-none rounded-lg bg-sky-800">
                        <p id="origin-country" class="p-1 font-semibold text-white"></p>
                    </div>
                </div>
                <div class="p-1 bg-zinc-300 rounded-b-lg">
                    <div class="p-1 pb-2 bg-zinc-300 ml-2 text-gray-800 space-y-1">
                    <p class="font-bold border-none text-lg  mb-2">Flight data:</p>
                    <p>Status: <label id="on-ground"></label> <label id="on-ground2"></label></p>
                    <p>Altitude: <label id="plane-alt"></label> m</p>
                    <p>Speed: <label id="plane-speed"></label> m/s</p>
                    <p>Degrees: <label id="plane-rotation"></label>°</p>
                    <p>Coordinates: <label id="plane-coords"></label>, <label id="plane-coords2"></label></p>
                    <p class="text-xs mt-3 text-gray-500 italic">Pēdējo reizi redzēta: <label id="last-contact"></label></p>
                </div>  
                </div>
            </div>
        </div>
       
    </div>
    
   
<script>
    const map = new maplibregl.Map({
        style: 'https://tiles.openfreemap.org/styles/liberty',
        center: [24.10589, 56.946],
        zoom: 5.5,
        container: 'map',
        attributionControl: false
    });
    

async function getLocation(){
        try {
            const response = await fetch('/api/proxy-flights');
            const result = await response.json();

        
            const geojson = {
            type: 'FeatureCollection',
           features: result.states.filter(plane => plane[5] !== null && plane[6] !== null).map(plane => ({
                type: 'Feature',
                properties: {
                        id:  plane[0],
                        callsign: plane[1],
                        origin_country: plane[2],
                        last_contact: plane[4], 
                        longitude: plane[5],
                        latitude: plane[6],
                        baro_altitude: plane[7] || 0,
                        on_ground: plane[8],  
                        velocity: plane[9],    
                        rotation: plane[10] - 20 || 0, 
                        geo_altitude: plane[13] || 0  
                    
                },
                geometry: {
                    type: 'Point',
                    coordinates: [plane[5], plane[6]]   
                }
            }))
        };
        
           
                if (map.getSource('plane-data')) {
                    map.getSource('plane-data').setData(geojson);
                }
    }   
    catch (error) {
        console.error("Radar Update Error:", error);
    }   

    }
       
       
map.on('load', async () => {
        
        const image = await map.loadImage('/storage/plane2.png');

        map.addImage('plane-image', image.data);


        map.addSource('plane-data', {
            type: 'geojson',
            data: { type: 'FeatureCollection', features: [] }   
        });

        map.addLayer({
            id: 'planes-id',
            type: 'symbol',
            source: 'plane-data',  
            layout: {
                'icon-image': 'plane-image',
                'icon-size': 0.12,
                'icon-rotate': ['get', 'rotation'],
                'icon-allow-overlap': true,
                'icon-rotation-alignment': 'map'
            }
        });
        
    getLocation();
    setInterval(getLocation, 30000);

    map.on('click', 'planes-id', (plane) => {

    

    const planeId = (plane.features[0].properties.id);
    const callSign = (plane.features[0].properties.callsign);
    const originCountry = (plane.features[0].properties.origin_country);
    const lastContact = (plane.features[0].properties.last_contact);
    const longitude = (plane.features[0].properties.longitude);
    const latitude = (plane.features[0].properties.latitude);
    const baro_altitude = (plane.features[0].properties.baro_altitude);
    const onGround = (plane.features[0].properties.on_ground);
    const velocity = (plane.features[0].properties.velocity);
    const rotation = (plane.features[0].properties.rotation);
    const geo_altitude = (plane.features[0].properties.geo_altitude);

    

    document.getElementById('plane-info').classList.remove('hidden');

    document.getElementById('plane-Id').innerHTML = callSign;
    document.getElementById('plane-callSign').innerHTML = planeId;
    document.getElementById('origin-country').innerHTML = originCountry;

    document.getElementById('on-ground').innerHTML = onGround ? "On ground" : "In the air";

    document.getElementById('last-contact').innerHTML = lastContact;
    document.getElementById('plane-coords').innerHTML = longitude;
    document.getElementById('plane-coords2').innerHTML = latitude;
    document.getElementById('plane-alt').innerHTML = Math.round(baro_altitude) || Math.round(geo_altitude);
    document.getElementById('plane-speed').innerHTML = Math.round(velocity);
    document.getElementById('plane-rotation').innerHTML = rotation;
    
    
    if (lastContact) {
        const date = new Date(lastContact * 1000);
        document.getElementById('last-contact').innerHTML = date.toLocaleTimeString();
    } else {
        document.getElementById('last-contact').innerHTML = "Unknown";
    }

    



});
map.on('mouseenter', 'planes-id', () => {
            map.getCanvas().style.cursor = 'pointer';
        });
    map.on('mouseleave', 'planes-id', () => {
            map.getCanvas().style.cursor = '';
        });
    
});





</script>

</x-layout>