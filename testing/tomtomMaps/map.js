// Define your product name and version
tomtom.setProductInfo('Codepen Examples', '4.47.6');
var markerOptions = {
    icon: tomtom.L.icon({
        iconUrl: 'https://api.tomtom.com/maps-sdk-js/4.47.6/examples/sdk/../img/icon.png',
        iconSize: [30, 34],
        iconAnchor: [15, 34]
    })
};
var map = tomtom.map('map', {
    key: 'u9cJAAejfXpbs298uzwInmjg9dvdMttZ',
    source: 'vector',
    basePath: 'https://api.tomtom.com/maps-sdk-js/4.47.6/examples/sdk'
});
tomtom.L.marker([43.26456, -71.5702], markerOptions).addTo(map).on('click', function(e){alert('k');
});

map.setView([39, -97.5], 4);
