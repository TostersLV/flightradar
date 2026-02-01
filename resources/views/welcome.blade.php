<x-layout>
    <div id="map" class="relative w-full h-screen z-10">
        
        
        <div id="plane-info" class="absolute hidden  z-40 bottom-0 rounded-lg w-full h-30 md:w-1/4 md:h-9/10 md:top-0 md:left-0 md:ml-5 md:mt-5">
            <div class="bg-gray-300 rounded-lg ">
                <div class="bg-gray-500 p-4  rounded-lg ">
                    <p id="plane-Id" class="text-yellow-300"></p>
                    <p id="plane-callSign"></p>
                </div>
                <div>
                    <p>d</p>
                    <p>d</p>
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
    });
    

async function getLocation(){
        try {
            const response = await fetch('/api/proxy-flights');
            const result = await response.json();

        
            const geojson = {
            type: 'FeatureCollection',
            features: result.states.map(plane => ({
                type: 'Feature',
                properties: {
                        id:  plane[0],
                        callsign: plane[1],
                        destination: plane[2],
                        on_ground: plane[8],
                        rotation: plane[10] || 0

                    
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

    document.getElementById('plane-info').classList.remove('hidden');

    document.getElementById('plane-Id').innerHTML = callSign;
    document.getElementById('plane-callSign').innerHTML = planeId;
    
    


    



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