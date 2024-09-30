let trackingInterval;

if (typeof sellerStatus !== 'undefined' && sellerStatus === 'open') {
    startLocationTracking();
}


document.addEventListener('DOMContentLoaded', function() {
    const toggleLocation = document.getElementById('toggleLocation');
    if (toggleLocation) {
        toggleLocation.addEventListener('change', function() {
            if (this.checked) {
                startLocationTracking();
            } else {
                closeLocation();
            }
        });
    }
});

function startLocationTracking() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(startTracking, handleError);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

function startTracking(position) {
    const { latitude, longitude } = position.coords;
    updateSellerLocation(latitude, longitude, 'open');


    trackingInterval = setInterval(function() {
        navigator.geolocation.getCurrentPosition(function(position) {
            const { latitude, longitude } = position.coords;
            updateSellerLocation(latitude, longitude, 'open');
        }, handleError);
    }, 15000);
}

function closeLocation() {
    if (trackingInterval) {
        clearInterval(trackingInterval);
    }
    updateSellerLocation(null, null, 'close');
}

function updateSellerLocation(latitude, longitude, status) {
    // Send location data to the backend using fetch
    fetch('../order_delivery/update_seller_location.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            latitude: latitude,
            longitude: longitude,
            status: status
        })
    })
    .then(response => response.text())
    .then(result => {
        if (result !== 'success') {
            alert('Failed to update location.');
        }
    })
    .catch(error => {
        console.error('Error during fetch:', error);
        alert('An error occurred while updating the location.');
    });
}

function handleError(error) {
    console.warn(`ERROR(${error.code}): ${error.message}`);
}
