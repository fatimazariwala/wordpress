globalThis = {};
var globalSave = true;

function getAddress(latitude, longitude) {
    // Construct the API URL
        var apiUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=AIzaSyBL3tCKWE9hgJE50EvpFiAshvJeYJy7bfU`;

        // Perform an HTTP request to the API
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
            if (data.status === "OK" && data.results.length > 0) {
                // Get the formatted address from the first result
                var address = data.results[0].formatted_address;
                var addressArray = data.results[1].address_components;
                
                globalThis = {
                    lat: latitude,
                    lon: longitude,
                    longAddress: address,
                    completeAddress: addressArray
                }

                console.log("Click");
            } else {
                console.error("Unable to retrieve address.");
            }
            })
            .catch(error => {
            console.error("Error fetching data:", error);
            });
        
    }

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
} 
else {
    console.log("Geolocation is not supported by this browser.");
}

function showPosition(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    getAddress(latitude,longitude);
}

const AjaxButton = document.getElementById('my_button');
const helpInfo = document.getElementById('helpInfo');
const infoForm = document.getElementById('input');
let inputCounter = 1;

AjaxButton.addEventListener("click", () => {

    const inputField = document.createElement('input');
    inputField.setAttribute('type', 'text');
    inputField.setAttribute('name', 'helpInfo' + inputCounter);
    inputField.setAttribute('placeholder', 'Enter the Emergency Help you need');
    inputField.style.width = '500px';
    inputField.setAttribute('required','required');

    helpInfo.appendChild(inputField);
    inputCounter++;

    const submitButton = document.createElement('input');
    submitButton.setAttribute('type', 'submit');
    submitButton.setAttribute('value', 'Submit');
    submitButton.setAttribute('id', 'my_submit_button');

    // Append the submit button to the form
    infoForm.appendChild(submitButton);

    infoForm.addEventListener("submit", (event)=> {

        event.preventDefault();

        const inputValue = inputField.value;

        //console.log(inputValue);

        if(inputValue !== ''){

            globalThis.helpInfo = inputValue;
        
            console.log(globalThis);
        }
            
    })
})


//window.addEventListener('load', showPosition);

