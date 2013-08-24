$(document).ready(function(){
    
    $("#upload-list-button").click(function(){
       
        type = $(this).attr("data-type");
       
        confirm_yn = confirm("Are you sure you want to upload a new list? \nThe data in these tables will be REPLACED by the new list.")
        if(!confirm_yn)
        {
            return;
        }
        uploadform = $("#upload-list-form");
        
        uploadform.submit();
    });
     
    
    $("#add-location").click(function(){
         $("#locations-map").css("visibility", "visible");
         var validator = $("#add-new-form").validate({
            rules: {
                name: "required",
                "Location[street_address]"  :  "required",
                "Location[city]"            :  "required",
                "Location[state]"           :  "required",
                "Location[zip_code]"        :  "required",
                phone_number                :  "required"
                // email                       :  "required"
            },
            messages: { 
                name: " Enter Name", 
                "Location[street_address]"  : " Enter Address", 
                "Location[city]"            : " Enter City", 
                "Location[state]"           : " Select a State", 
                "Location[zip_code]"        : " Enter Zip",
                phone_number                : " Phone number is required"
                // email: " Enter an email"
            }
        });
        if(!validator.form())
        {
            return;
        }
        zipcodecenter = {"lat":"39.6602","lng":"-104.78"};
        var mapOptions = {
            center: new google.maps.LatLng(zipcodecenter.lat, zipcodecenter.lng),
            zoom: 7,
            disableDefaultUI: true,
            scaleControl: false,
            draggable: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("locations-map"), mapOptions);
        
        formdata = $("#add-new-form").serializeObject();
        
        address = formdata["Location[street_address]"];
        city = formdata["Location[city]"];
        state = formdata["Location[state]"];
        zip_code = formdata["Location[zip_code]"];
        
        geocoderObj = new google.maps.Geocoder();
        
        geoaddress = address + " " + city + ", " + state + " " + zip_code;
        geocoderObj.geocode({address : geoaddress}, function(results, status){
            if (status == google.maps.GeocoderStatus.OK) 
            { 
                 map.setCenter(results[0].geometry.location);
                 map.setZoom(14);  
                 
                 var marker = new google.maps.Marker({
                    position: results[0].geometry.location,
                    map: map
                    // title:locationlist.labs[lab].name
                });
                $("#add-location-submit").show();
                
                html = address + "<br>";
                html += city + ", ";
                html += state + " ";
                html += zip_code + "<br>";
                
                $("#lat").val(results[0].geometry.location.lat());
                $("#lng").val(results[0].geometry.location.lng());
                
                $("#location-feedback").html(html);
                $("#add-new-form input").change(function(){
                     $("#add-dealer-submit").hide();
                     $("#location-feedback").html("");
                })
            }
        });
    });
});


$.fn.serializeObject = function()
{
    var o = {};
    var a = $(this).serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};