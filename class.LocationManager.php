<?php
    require_once("helpers/location_helper.php");
    class LocationManager
    {
        public $locationtable   = "geo_locations";
        public $ziptable        = "geo_zip_code";
            
        public function addLocation($locationdata, $type)
        {
            $locationtable = $this->locationtable;
            
            $street_address = $locationdata["street_address"];	
            $city = $locationdata["city"];
            $state = $this->standardize_state_to_abbr($locationdata["state"]); 	
            $zip_code = $this->standardize_zip($locationdata["zip_code"]); 	
            $lat = $locationdata["lat"]; 	
            $lng = $locationdata["lng"];
            
            $sql = "INSERT INTO $locationtable (street_address, city, state, zip_code, type, lat, lng)
                VALUES ('$street_address', '$city', '$state', '$zip_code', '$type', '$lat', '$lng')";
            $result = mysql_query($sql);
            
            if($result)
            {
                return mysql_insert_id();
            }
            else
            {
                return 0;
            }
        }
        
        public function getLocationsInRange($range_from, $range_to, $zip, $limit=null)
        {
            $coords = $this->getZipCoords($zip);
            $lat = $coords["lat"];
            $lng = $coords["lng"];
            $locationtable = $this->locationtable;
            
            $limitclause = "";
            if(!is_null($limit) && is_numeric($limit))
            {
               $limitclause = "LIMIT " . $limit;
            }
            $sql = "SELECT 3956 * 2 * ATAN2(SQRT(POW(SIN((RADIANS($lat) - "
              .'RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * '
              ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lng) - "
              ."RADIANS(z.lng)) / 2), 2)), SQRT(1 - POW(SIN((RADIANS($lat) - "
              ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
              ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lng) - "
              ."RADIANS(z.lng)) / 2), 2))) AS \"miles\", z.* FROM $locationtable z "
              ."WHERE lat BETWEEN ROUND($lat - (25 / 69.172), 4) "
              ."AND ROUND($lat + (25 / 69.172), 4) "
              ."AND lng BETWEEN ROUND($lng - ABS(25 / COS($lat) * 69.172)) "
              ."AND ROUND($lng + ABS(25 / COS($lat) * 69.172)) "
              ."AND 3956 * 2 * ATAN2(SQRT(POW(SIN((RADIANS($lat) - "
              ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
              ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lng) - "
              ."RADIANS(z.lng)) / 2), 2)), SQRT(1 - POW(SIN((RADIANS($lat) - "
              ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
              ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lng) - "
              ."RADIANS(z.lng)) / 2), 2))) <= $range_to "
              ."AND 3956 * 2 * ATAN2(SQRT(POW(SIN((RADIANS($lat) - "
              ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
              ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lng) - "
              ."RADIANS(z.lng)) / 2), 2)), SQRT(1 - POW(SIN((RADIANS($lat) - "
              ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
              ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lng) - "
              ."RADIANS(z.lng)) / 2), 2))) >= $range_from "
              ."ORDER BY 1 ASC $limitclause"; 
              
                $result = mysql_query($sql);
                $a = array();
                while ($row = mysql_fetch_array($result))
                {
                    $a[$row['miles']] = $row;
                }
                return $a;
        }
        
        public function getZipsInRange($range_from, $range_to, $zip)
        {
            $coords = $this->getZipCoords($zip);
            $lat = $coords["lat"];
            $lng = $coords["lng"];
            $ziptable = $this->ziptable;
            
            $sql = "SELECT 3956 * 2 * ATAN2(SQRT(POW(SIN((RADIANS($lat) - "
              .'RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * '
              ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lng) - "
              ."RADIANS(z.lng)) / 2), 2)), SQRT(1 - POW(SIN((RADIANS($lat) - "
              ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
              ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lng) - "
              ."RADIANS(z.lng)) / 2), 2))) AS \"miles\", z.* FROM $ziptable z "
              ."WHERE zip_code <> '$zip' " 
              ."AND lat BETWEEN ROUND($lat - (25 / 69.172), 4) "
              ."AND ROUND($lat + (25 / 69.172), 4) "
              ."AND lng BETWEEN ROUND($lng - ABS(25 / COS($lat) * 69.172)) "
              ."AND ROUND($lng + ABS(25 / COS($lat) * 69.172)) "
              ."AND 3956 * 2 * ATAN2(SQRT(POW(SIN((RADIANS($lat) - "
              ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
              ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lng) - "
              ."RADIANS(z.lng)) / 2), 2)), SQRT(1 - POW(SIN((RADIANS($lat) - "
              ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
              ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lng) - "
              ."RADIANS(z.lng)) / 2), 2))) <= $range_to "
              ."AND 3956 * 2 * ATAN2(SQRT(POW(SIN((RADIANS($lat) - "
              ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
              ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lng) - "
              ."RADIANS(z.lng)) / 2), 2)), SQRT(1 - POW(SIN((RADIANS($lat) - "
              ."RADIANS(z.lat)) / 2), 2) + COS(RADIANS(z.lat)) * "
              ."COS(RADIANS($lat)) * POW(SIN((RADIANS($lng) - "
              ."RADIANS(z.lng)) / 2), 2))) >= $range_from "
              ."ORDER BY 1 ASC"; 
              
                $result = mysql_query($sql);
                $a = array();
                $match = false;
                while ($row = mysql_fetch_array($result))
                {
                    if($row["zip_code"] == $zip)
                    {
                        $match = true;
                    }
                    $a[$row['miles']] = $row;
                }
                
                // make sure the requested zip is represented in the 
                if(!$match)
                {
                    $fakerow = array(
                        "miles" => 0.0,
                        "zip_code" => $zip,
                    );
                    $a[$row['miles']] = $fakerow;   
                }
                return $a;
        }
        
        public function getZipCoords($zip)
        {
            $ziptable = $this->ziptable;
            $sql = "SELECT * FROM  $ziptable WHERE zip_code = '$zip'";
            $result = mysql_query($sql);
            
            $row_count = mysql_num_rows($result);
            if($row_count > 0)
            {
               while($row = mysql_fetch_assoc($result))
                {
                    $coords["lat"] = $row["lat"];
                    $coords["lng"] = $row["lng"];
                    return $coords;
                } 
            }
            else
            {
                // TODO: If we don't have an entry for the given zip see if we can geocode it
                $coords = $this->attemptGeoCodeZip($zip);
                if(is_array($coords))
                {
                    return $coords;
                }
                elseif(is_string ($coords))
                {
                    return $coords;
                }
                else
                {
                    return "This zip code could not be located. Please check your zip entry.";
                }
            }
            
        }
        
        public function addNewZip($zip, $coords)
        {
            $zip = str_pad($zip, 5, 0, STR_PAD_LEFT);
            $ziptable = $this->ziptable;
            $foundlat = $coords["lat"];
            $foundlng = $coords["lng"];
            $sql = "INSERT INTO $ziptable (zip_code, lat, lng) VALUES ('$zip', '$foundlat', '$foundlng')";
            
            mysql_query($sql); 
        }
        
        public function getCityState($zip)
        {
             $valid = true; //$this->validateZip($zip);
             if($valid)
            {
                $zip = str_pad($zip, 5, 0, STR_PAD_LEFT);
                $sql = "SELECT city, state_prefix FROM geo_zip_code WHERE zip_code = '$zip'";
                $result = mysql_query($sql);
                $response = array();
                if(mysql_numrows($result))
                {
                    $response["success"] = 1;
                    while($row = mysql_fetch_assoc($result))
                    {
                        $city = $row["city"];
                        $state = $row["state_prefix"];
                        
                    }
                    $response["message"] = $city . ", " . $state;
                }
                else
                {
                    $response["success"] = 0;
                    $response["message"] = "We did not find that City in our database.";
                }
                return $response;
            }
            $response["success"] = 0;
            $response["message"] = "Please check your City State input";
            return $response;
             
        }
        public function getZip($citystate)
        {
            $valid = true; //$this->validateCityState($citystate);
            $citystate_parts = explode(",", $citystate);
            if(count($citystate_parts) > 1)
            {
                $city = ucwords(strtolower(trim($citystate_parts[0])));
                $state = ucwords(strtolower(trim($citystate_parts[1])));
                if(strlen($state) > 2)
                {
                    $state = $this->standardize_state_to_abbr($state);
                }
                else
                {
                    $state = strtoupper($state);
                }
            }
            else
            {
               $valid = false;
            }
           
            
            
            if($valid)
            {
                $sql = "SELECT zip_code FROM geo_zip_code WHERE city = '$city' AND  state_prefix = '$state' LIMIT 1";
                $result = mysql_query($sql);
                $response = array();
                if(mysql_numrows($result))
                {
                    $response["success"] = 1;
                    while($row = mysql_fetch_assoc($result))
                    {
                        $zip = $row["zip_code"];
                    }
                    $response["message"] = $zip;
                }
                else
                {
                    $response["success"] = 0;
                    $response["message"] = "We did not find that City in our database.";
                }
                return $response;
            }
            $response["success"] = 0;
            $response["message"] = "Please check your City, State input";
            return $response;
        }
        
        public function sanitize($postdata)
       {
           foreach($postdata as $key => $value)
           {
               if(is_string($value))
               {
                   $postdata[$key] = mysql_real_escape_string($value);
                   $postdata[$key] = strip_tags($value); 
                   $postdata[$key] = trim($value); 
                   if($key == "zip_code")
                   {
                       $postdata[$key] = str_pad($value, 5, 0, STR_PAD_LEFT);
                   }
               }
               elseif(is_array($value))
               {
                   $postdata[$key] = $this->sanitize($value);
               }
           }
           return $postdata;
       }
       
        function standardize_phone($input)
        {
            $out = preg_replace("/[^0-9]/","", trim($input));
            $length = strlen($out);

            if($length == 7) {
                $regex = '/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/';
                $replace = '$1-$2';
            } elseif($length == 10) {
                $regex = '/([0-9]{3})([0-9]{3})([0-9]{4})/';
                $replace = '($1) $2-$3';
            } elseif($length > 10) {
                $regex = '/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/';
                $replace = '$1 ($2) $3-$4';
            }
            else
            {
                $regex = '/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/';
                $replace = '$1 ($2) $3-$4';
            }

            $out = preg_replace($regex, $replace, $out);
            return $out;
        } 
        
        function standardize_zip($input)
        {
            // only look at the first five characters
            $out = preg_replace("/[^0-9]/", "", $input);
            $length = strlen($out);
            
            if($length <= 5 && $length != 0)
            {
                 $out = str_pad($out, 5, 0, STR_PAD_LEFT);
                 return $out;
            }
            elseif($length > 5)
            {         
                $out = substr($out, 0, 5);
                return $out;
            }
        }
        
        // for the case when the input is the full state and we need abbreviation
        function standardize_state_to_abbr($input)
        {
            $inputUC = strtoupper($input);
            // make state name the key and abbr the value
            $states_array = array_flip(Location_helper::$states);
            $states_array = array_change_key_case($states_array, CASE_UPPER);
            
            if(array_key_exists($inputUC, $states_array))
            {
                return $states_array[$inputUC];
            }
            else
            {
                return $input;
            }
        }
         // for the case when the input is abbreviation and we need full state name
        function standardize_state_to_full($input)
        {
            $inputUC = strtoupper($input);
            $states_array = Location_helper::$states;
            
             if(array_key_exists($inputUC, $states_array))
            {
                return $states_array[$inputUC];
            }
            else
            {
                return $input;
            }
        }
        
        function remove_commas($input)
        {
            $out = str_replace($input);
            return $out;
        }
        
        
       public function validateCityState($zip)
       {
           //$match = preg_match("/[0-9]{5}/", $zip);
           //return $match;
       }
       
       public function validateZip($zip)
       {
           $match = preg_match("/[0-9]{5}/", $zip);
           return $match;
       }
       
       public function validateRadius($radius)
       {
           $match = preg_match("/[0-9]/", $radius);
           return $match;
       }
       
       public function validateEmail($email)
       {
           return filter_var($email, FILTER_VALIDATE_EMAIL);
       }
       
        // Used to make sure the zipcode is in the database before instantiating the zipcode class
       public function testZip($zip)
       {
           $ziptable = $this->ziptable;
           $sql = "SELECT * FROM $ziptable WHERE zip_code = $zip";
           $result = mysql_query($sql);
             
           if(!$result || mysql_num_rows($result) > 0 )
           {  
               return true;
           }
           else
           {
               $this->attemptGeoCode($zip);
           }
       }
       
        public function attemptGeoCodeZip($zip)
       {
           $ziptable = $this->ziptable;
           
           $coords = $this->geocodeGoogle($zip);
           
//            // try Yahoo
//            if($coords == 0)
//            {
//                $coords = $this->geocodeYahoo($url);
//            }
//            
//            // try MSN
//            if($coords == 0)
//            {
//                $coords = $this->geocodeMSN($url);
//            }   
 
            // if we end up with a result save it to the database
            if($coords != 0)
            {
               $this->addNewZip($zip, $coords);
               return $coords;
            }
            else
            {
                return "This zipcode doesn't exist";
            }
       }
       
       // TODO: if we exceed our usage limit we will have to find a way to go to next geocode service
       private function geocodeGoogle($zip)
       {
            $url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $zip . "&sensor=false";
            // echo $url;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            $geoloc = json_decode($data, true);
            // echo $data;
            // var_dump($geoloc);
            if(!empty($geoloc['results'][0]))
            {
                $coords["lat"] = $geoloc['results'][0]['geometry']['location']['lat'];
                $coords["lng"] = $geoloc['results'][0]['geometry']['location']['lng'];
                if($coords["lat"] > 0 && $coords["lng"] >0)
                {
                    return $coords;
                }
                else
                {
                    return 0;
                }
            }
            else
            {
                return 0;
            }
       }
       
       // used by location_ajax_api to update missing coords
        function update_location_coords($postdata)
        {
           $locationtable = $this->locationtable;
                      
            $lat = $postdata["lat"];
            $lng = $postdata["lng"];
            $id = $postdata["location_id"];
            
            $sql = "SELECT lat, lng FROM $locationtable WHERE id = $id LIMIT 1";
            $result = mysql_query($sql);
            if(!$result)
            {
                exit();
            }
            $row = mysql_fetch_array($result);
            if($row["lat"] > 0 && $row["lng"] > 0)
            {
                exit();
            }
            $sql = "UPDATE $locationtable SET lat='$lat', lng='$lng' WHERE id = $id LIMIT 1";
            
            mysql_query($sql);
        }
        
        
        public function exportData($data, $headers, $filename = "Export_Data")
        {
            header("Content-type: text/csv"); 
            header("Content-Disposition: attachment; filename=$filename.csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            $outstream = fopen("php://output", "w");
            $headerline = implode(",", $headers);
            echo $headerline . "\r\n";     
            while($row =  mysql_fetch_assoc($data))
            {
                $rowline = implode(",", $row);
                $rowline = preg_replace('/\n/', ' ', $rowline);
                $rowline = preg_replace('/\r/', ' ', $rowline);
                echo $rowline . "\r\n";
            }
            fclose($outstream);
            exit();
        }
        
       public function uploadList($postdata, $acceptableHeaders)
       {
            $file = $_FILES["upload_file"];
            $filename = $file['name'];
            $src = $file['tmp_name'];
            $dest = "uploads/" . $filename;
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            
            $location_list = array();
            $allgood = true;
            $errors = array();
            $stufftoreturn = "";
            
            if($ext != "csv")
            {
                return  "Please choose a CSV file to upload.";
            }

            move_uploaded_file($src, $dest);
            $filedata = fopen($dest, "r");
            $linecount = 0;
            $headers = array();
            while (!feof($filedata))
            {
                
                $values = fgetcsv($filedata);
                // var_dump($values);
                
//                $line = fgets($filedata);
//                $values = explode(",", $line);
                
                // get headers from first line create error if header don't match
                if($linecount == 0)
                {
                    $i = 0;
                    foreach($values as $value)
                    {
                        $headeritem = trim($value);
                        if(count($values) > count($acceptableHeaders))
                        {
                            return "ERROR: Mismatch header count";
                            $allgood = false;
                        }
                        else
                        {
                            if($headeritem != $acceptableHeaders[$i])
                            {
                                return "$values <br>ERROR: Header Mismatch:<br>Please verify correct headers and try upload again. <br>'$headeritem' does not match '".$acceptableHeaders[$i]."'<br>";
                                $allgood = false;
                            }
                            array_push($headers, $headeritem);
                            $i++;
                        }
                    }
                }
                elseif($allgood)
                {
                    $row = $linecount + 1;
                    $correctcolcount = count($headers);
                    $countrowvalues = count($values);
                               
                    if($correctcolcount != $countrowvalues)
                    {
                        if(!empty($values))
                        {
                            return "ERROR: Field Count error on Row $row. <br>Check for commas in your data";
                        }
                    }
                    elseif($correctcolcount == $countrowvalues)
                    {
                        if(!empty($values))
                        {
                            $i = 0;
                            foreach($headers as $header)
                            {
                                $location_list[$linecount][$header] = trim($values[$i]);
                                $i++;
                            }
                        }
                    }
                    
                }
                $linecount++;
            }
            if(count($errors) > 0)
            {
                return $errors;
            }
            else
            {
                return $location_list;
            }
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
       
        
        
       private function geocodeYahoo($url)
       {
           
       }
       private function geocodeMSN($url)
       {
           
       }
       
       
    }
?>
