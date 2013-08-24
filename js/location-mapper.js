$(document).ready(function(){ 
        zoompresets = {
            0 :     3,
            10 :    10,
            25:     9,
            50:     7,
            100:    6
        }

        var zipcodecenter = {"lat":"39.6602","lng":"-97.78"};

    //    $("#FPMap0 area").click(function(){
    //    });

        var markerarray = {};
        var mapOptions = {
            center: new google.maps.LatLng(zipcodecenter.lat, zipcodecenter.lng),
            zoom: 3,
            // disableDefaultUI: true,
            // scaleControl: false,
            draggable: false,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("location-map"), mapOptions);
        var infowindow = new google.maps.InfoWindow({
            content: "",
            maxWidth: 320 
        });

        $(".map-it-link").click(function(){
        $("#location-map").show();
        locationid = $(this).attr("data-which-location");
        mapit(locationid);
        }); 
    
        mapit = function(locationid)
        {
            lat = branch_lookup[locationid].lat;
            lng = branch_lookup[locationid].lng;
            $("#location-details").html("");
            if(!lat || parseInt(lat) == 0)
            {
                result = updategeocode(locationid);
                return;
            }
            
            mapitLatLng = new google.maps.LatLng(lat, lng);

            map.setCenter(mapitLatLng);
            map.setZoom(12);

            param = branch_lookup[locationid].street_address + " " + branch_lookup[locationid].city + " " + branch_lookup[locationid].state + " " + branch_lookup[locationid].zip_code;  
            href = "http://maps.google.com/maps?daddr=" + param;
            link = "<a target='_blank' href='"+href+"'>Directions</a>";
            citystatezip = branch_lookup[locationid].city + ", " + branch_lookup[locationid].state + " " + branch_lookup[locationid].zip_code;
            address = branch_lookup[locationid].street_address + "<br>";
            
            html = "<div class='details-col'>";
                html += address;
                html += citystatezip;
                html += "<br>" + link + "<br>";

                if(branch_lookup[locationid].phone_numbers)
                {
                    html += branch_lookup[locationid].phone_numbers;
                }

            html += "</div>";
            
            infowindow.content =  html;
            infowindow.open(map, markerarray[locationid]);
        }
        
        addmarker = function(l_id, thislat, thislng)
        {
            locationLatLng = new google.maps.LatLng(thislat, thislng);
            var marker = new google.maps.Marker({
                position: locationLatLng,
                map: map,
                locationid: l_id
                // cursor: "default"
                // title:locationlist.labs[lab].name
            });
            google.maps.event.addListener(marker, 'click', function() {
                $(".branch-choice").parent().removeClass("selected");
                $(".branch-choice[data-branch-id=" + this.locationid +"]").parent().addClass("selected");
                mapit(this.locationid);
            });
            markerarray[l_id] = marker;
        }
        
        updategeocode = function(locationid)
        {
            newlat = 0;
            newlng = 0;
            geocoderObj = new google.maps.Geocoder();
            locationdata = branch_lookup[locationid];
            geoaddress = locationdata.street_address + " " + locationdata.city + " " + locationdata.state + " " + locationdata.zip_code;
           
            geocoderObj.geocode({address : geoaddress}, function(results, status){
            if (status == google.maps.GeocoderStatus.OK) 
            { 
                // console.log(results[0]);
                newlat = results[0].geometry.location.lat();
                newlng = results[0].geometry.location.lng();

                addmarker(locationid, newlat, newlng);
                branch_lookup[locationid].lat=newlat;
                branch_lookup[locationid].lng=newlng;
               
                locationdata = {
                    "location_id"   : locationid, 
                    "lat"           : newlat, 
                    "lng"           : newlng,
                    "request_type"  :"updategeocode"
                    }
                    $.ajax({
                        type: "POST",
                        url: "locationmanager/location_ajax_api.php",
                        data: locationdata,
                        success: function(response)
                        {
                            mapit(locationid);
                        }
                    });
                    return "success";
                }
                else
                {
                        $("#location-directions-link").html("Unable to Geocode this address");
                }
            });
        }
    
    
        showStateBranches = function(whichstate)
        {
            clearmarkers();
            $("#zipcode").val("");
            idealzoom = statelookup[whichstate].idealzoom - 1;
            mapitLatLng = new google.maps.LatLng(statelookup[whichstate].coords.lat, statelookup[whichstate].coords.lng);

            map.setCenter(mapitLatLng);
            map.setZoom(idealzoom);

            branch_set = branchesByStateJSON[whichstate];
            html = "";
            count = 0;
            for(branch in branch_set)
            {
                html += "<div class='results-row'>";
                    if(branch_set[branch].lat && parseInt(branch_set[branch].lat) != 0)
                    {
                        addmarker(branch_set[branch].id, branch_set[branch].lat, branch_set[branch].lng);
                    }
                    else
                    {
                        result = updategeocode(branch_set[branch].id);
                    }

                    citystatezip = branch_lookup[branch].city + ", " + branch_lookup[branch].state + " " + branch_lookup[branch].zip_code;
                    address = branch_set[branch].street_address + " ";
                    phone_numbers = "";
                    if(branch_lookup[branch].phone_numbers)
                    {
                        phone_numbers += "<span class='phonediv'>" + branch_lookup[branch].phone_numbers + "</span>";
                    }

                    html += "<p class='branch-choice'>";
                        html += "<a class='branch-choice' data-branch-id='"+branch_set[branch].id+"'>" + address + "<br>" + citystatezip;
                        html += phone_numbers  + "</a>";
                    html += "</p>";
                    
                html += "</div>";
                count ++;
                if(count == 2)
                {
                    html += "<div class='clear-cols'></div>";
                    count = 0;
                }
            }
            
            return html;
        }

        clearmarkers = function()
        {
            for(marker in markerarray)
            {
            markerarray[marker].setMap(null)
            }
        }

    
        if(js_byzip)
        {
            html = "";
            branch_set = js_byzip.list;
            
            for(branch in branch_set)
            {
                branchid = branch_set[branch].id;
            
                if(branch_set[branch].lat && parseInt(branch_set[branch].lat) != 0)
                {
                    addmarker(branch_set[branch].id, branch_set[branch].lat, branch_set[branch].lng);
                }
                else
                {
                    result = updategeocode(branch_set[branch].id);
                }

                citystatezip = branch_lookup[branchid].city + ", " + branch_lookup[branchid].state + " " + branch_lookup[branchid].zip_code;
                address = branch_set[branch].street_address + " ";
                phone_numbers = "";
                if(branch_lookup[branchid].phone_numbers)
                {
                    phone_numbers += "<span class='phonediv'>" + branch_lookup[branchid].phone_numbers + "</span>";
                }
                html += "<div class='results-row'>";
                    html += "<p class='branch-choice zip-search'>";
                        html += "<a class='branch-choice' data-branch-id='"+branch_set[branch].id+"'>" + address + "<br>" + citystatezip;
                        html += phone_numbers  + "</a>";
                    html += "</p>";
                    html += "<div class='clear-cols'></div>";
                html += "</div>";
            }
            
            $("#map-feedback").html(html);
            idealzoom = statelookup[branch_lookup[branchid].state].idealzoom - 1;
            coords = statelookup[branch_lookup[branchid].state].coords;
            
            newLatLng = new google.maps.LatLng(coords.lat, coords.lng);
            map.setCenter(newLatLng);       
            map.setZoom(idealzoom);
           
            $("a.branch-choice").unbind("click");
            $("a.branch-choice").click(function(){
                $("a.branch-choice").parent().removeClass("selected");
                $(this).parent().addClass("selected");
                branch_id = $(this).attr("data-branch-id");
                mapit(branch_id);
            });
//           setTimeout(
//                function(){
//                   $("a.branch-choice").trigger("click");
//                },750
//            );
        }
        
        
        // check to see state variable was set in get variable
        if(js_state != "")
        {
            whichstate = js_state;
            $("#location-details").html("");
//            whichstate = $(this).attr("data-state");
            html = showStateBranches(whichstate);
            $("#map-feedback").html(html);

            $("a.branch-choice").unbind("click");
            $("a.branch-choice").click(function(){
                $("a.branch-choice").parent().removeClass("selected");
                $(this).parent().addClass("selected");
                branch_id = $(this).attr("data-branch-id");
                mapit(branch_id);
            });
        }
    
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

