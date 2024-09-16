<script>
    let autocomplete;

    function initAutocomplete() {
        let address1Field = document.querySelector("#ship-address");

        autocomplete = new google.maps.places.Autocomplete(address1Field, {
            fields: ["address_components", "geometry"],
            types: ["address"],
        });
        address1Field.focus();
        autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        const place = autocomplete.getPlace();
        console.log(place);
        let street = '';
        let streetNumber = '';
        let zipCode = '';
        let city = '';
        let country_code = '';
        for (const object of place.address_components) {
            if (object.types.includes("street_number") || object.types.includes("intersection")) {
                streetNumber = object.long_name;
            }
            if (object.types.includes("route") || object.types.includes("sublocality")) {
                street = object.long_name;
            }
            if (object.types.includes("locality") || object.types.includes("administrative_area_level_3")) {
                city = object.long_name;
            }
            if (object.types.includes('country')) {
                country_code = object.short_name;
            }
            if (object.types.includes("postal_code")) {
                zipCode = object.long_name;
            }
        }

        street += (streetNumber) ? " " + streetNumber : "";
        $('#street').val(street);
        $('#zip').val(zipCode);
        $('#city').val(city);
        $('#country_code').val(country_code);
        $('#latitude').val(place.geometry.location.lat());
        $('#longitude').val(place.geometry.location.lng());
    }

    window.initAutocomplete = initAutocomplete;
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&callback=initAutocomplete&libraries=places&v=weekly" defer ></script>
