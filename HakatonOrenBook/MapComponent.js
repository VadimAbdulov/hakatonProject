import { useEffect } from 'react';
import { WebMap } from '@nextgis/webmap';

function MapComponent() {
    useEffect(() => {
        const map = new WebMap({
            target: 'map', 
            basemap: 'osm',
        });

        map.addLayer({
            type: 'Vector',
            url: 'https://geois2.orb.ru/api/resource/8563/feature/',
            headers: {
                'Authorization': 'Basic aGFja2F0aG9uXzExOmhhY2thdGhvbl8xMV8yNQ==', 
                'Accept': 'application/json',
            },
        });
    }, []);

    return <div id="map" style={{ width: '100%', height: '500px' }}></div>;
}

export default MapComponent;
