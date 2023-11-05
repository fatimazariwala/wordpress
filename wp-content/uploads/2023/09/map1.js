console.log("i am running initMap2");
let map;

async function initMap2() {

  var marker;
  var pos = { lat:19.0217, lng: 72.8699};
  var geocoder = new google.maps.Geocoder();
  const { Map } = await google.maps.importLibrary("maps");

  map = new Map(document.getElementById("map-for-users"), {
    center: pos,
    zoom: 10,
  });

  const infoForm = document.getElementById('input');
  const locationButton = document.createElement("button");
  locationButton.textContent = "Pans to Current Location";
  locationButton.classList.add("custom-map-control-button");
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);

  locationButton.addEventListener("click",(event)=>{

    var address = globalThis['longAddress'];
    var address_title = globalThis['completeAddress'][1].short_name;
    pos = {lat:globalThis['lat'],lng:globalThis['lon']};
    map.setCenter(pos);

    marker = new google.maps.Marker({
      position : pos,
      map : map,
      draggable : true,
    });

    const infowindow = new google.maps.InfoWindow({
      content: "<b>" + address_title + "</b><br>" + address,
      ariaLabel: address_title,
    });

    infowindow.open({
        anchor: marker,
        map,
    });

    marker.addListener('click',()=>{
      infowindow.open({
        anchor: marker,
        map,
      });
    });

    const circle = new google.maps.Circle({
      strokeColor: "#FF0000",
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: "#FF0000",    
      fillOpacity: 0.35,
      map,
      center: pos,
      radius: (1/2**(map.getZoom()))*10**(7),
    });

    map.addListener("zoom_changed", () => {
            circle.setRadius((1/2**(map.getZoom()))*10**(7));
            map.panTo(pos); 
    });

    marker.addListener("dragend", () => {
      var lat, lng, address_formatted,address_array,address_title;
      geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          lat = marker.getPosition().lat();
          lng = marker.getPosition().lng();
          address_formatted = results[0].formatted_address;
          address_array = results[1].address_components;
          address_title = address_array[1].short_name;

          pos = new google.maps.LatLng(lat,lng);
          infowindow.setContent("<span style='color:green;'><b>MANUALLY EDITTED</b></span><br><b>" + address_title + "</b><br>" + address_formatted);
          circle.setCenter(pos);
          circle.setOptions({
            strokeColor: '#5ebd6b',
            fillColor: '#5ebd6b'
          });
        }
      })
    })
  })

  infoForm.addEventListener("submit", (event)=> {
    console.log("I AM BEGINing CLICK");
    var lat, lng, address_formatted,address_array,address_title;
    geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        lat = marker.getPosition().lat();
        lng = marker.getPosition().lng();
        address_formatted = results[0].formatted_address;
        address_array = results[1].address_components;
        address_title = address_array[1].short_name;         
  
        if(confirm("REQUEST HELP at '" +  address_title + "'???") == true){
    
          currentPosition = {
            'latitude': lat,
            'longitude': lng,
            'address': address_formatted,
            'addressArray': address_array,
            'helpInfo' : globalThis['helpInfo']
          }

          jQuery.ajax({
            type:"post",
            url: geo.ajaxurl,
            data:{
                action:"currentData_action",
                data: currentPosition
            },
            complete: function(response){
              console.log("CURRENT DATA",response);
              console.log(response.responseJSON);

              var response = response.responseJSON;
                if(response == 'alreadyExists'){
                  if(confirm('LOCATION already EXIST!! Do you want to update another one?') == true){
                    jQuery.ajax({
                        type:"post",
                        url: update_geo.ajaxurl,
                        data:{
                            action:"updateData_action",
                            data: currentPosition
                        },
                        complete: function(response){
                            console.log("UPDATE NEW DATA",response);
                        },
                        error: function(error){
                            console.error("UPDATED AJAX error :", error);
                            console.log("second query : ",globalThis);
                        }
                    });
                  }
                }
              },
            error: function(error){
              console.error("EDIT AJAX error :", error);
            }
          });
        }
      }
    })
  })

