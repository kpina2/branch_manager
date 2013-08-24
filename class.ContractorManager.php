<?php
    require_once("class.dbconn.php");
    require_once("class.LocationManager.php");
    class ContractorManager extends LocationManager
    {
        public $default_email_from = "noreply@gulfeaglesupply.com";
        public $default_email_message = "";
        
        public $default_email_layout = array(
            "from" => "noreply@gulfeaglesupply.com",
            
            "bcc" => "",
            
            "subject" => "Your Contractor Results",
            
            "style" => "h2{} p{}", // for including simple styling
            
            "logo" => "", // absolute path to image DO NOT USE TAGS! Example: http://tester6.com/img/logo.png
           
            "title" => "", // Sort of a headline for the email
            
            "message_before_list" => "", 
            
            "message_after_list" => "", 
            
            "footer" => "" // Use li tags or other means of separating content
        );
        // STYLE GUIDE 
        // 'title' is done with h2 tag
        // <p> tags separate sections
        // You may add tags in other parts of the message and style them as well
        
        public $type = "contractor";
        public $locationtable   = "geo_locations";
        public $contractortable   = "geo_contractors";
        public $defaultemail = "";
        public $acceptableHeaders = array(
                "Company Name",
                "Street Address",
                "City",	
                "State",	
                "Zip Code",	
                "Phone Number",
                "Email",
                "Website"
            );
        
        function uploadList($postdata) {
            $contractor_list = parent::uploadList($postdata, $this->acceptableHeaders);
            if(is_array($contractor_list))
            {
                return $this->process_list($contractor_list);
            }
            else
            {
                return $contractor_list;
            }
            
        }
        function process_list($contractor_list)
        {
            if(count($contractor_list) > 0)
            {
                $this->reset_contractors();
                $contractor_count = 0;
                
                foreach($contractor_list as $index => $contractor)
                {
                    $contractor_count ++;
                    
                    $locationdata["Location"]["street_address"] = $contractor["Street Address"];
                    $locationdata["Location"]["city"] = $contractor["City"];
                    $locationdata["Location"]["state"] = $contractor["State"];
                    $locationdata["Location"]["zip_code"] = (string)$contractor["Zip Code"];
                    $locationdata["Location"]["lat"] = $lat = 0.0;
                    $locationdata["Location"]["lng"] = $lng = 0.0;
                    
                    $locationdata["name"] = $contractor["Company Name"];
                    $locationdata["email"] = $contractor["Email"];
                    $locationdata["website"] = $contractor["Website"];
                    $locationdata["phone_number"] = $this->standardize_phone($contractor["Phone Number"]);
                    
                    $this->addLocation($locationdata);
                }
                return "Succesfully Loaded $contractor_count ".$this->type."(s)";
            }
            else
            {
                return "Empty File";
            }
        }
        private function reset_contractors()
        {
            $contractortable = $this->contractortable;
            $locationtable = $this->locationtable;
            $type = $this->type;
            
            $sql = "TRUNCATE TABLE $contractortable";
            
            $result = mysql_query($sql);
            
            $sql = "DELETE FROM $locationtable WHERE type = '$type'";
            $result = mysql_query($sql);
        }
        
        public function exportData()
        {
            $contractortable = $this->contractortable;
            $locationtable = $this->locationtable;
            $sql = "SELECT name, street_address, city, state, zip_code, phone_number, email, website
                FROM $contractortable
                JOIN $locationtable ON $contractortable.location_id = $locationtable.id";
            $result = mysql_query($sql);
            $filename = "Contractor_Export";
            parent::exportData($result, $this->acceptableHeaders, $filename);
           
        }
        
        public function addLocation($postdata) {
            
            $newid = parent::addLocation($postdata["Location"], $this->type);
            $contractortable = $this->contractortable;
            if($newid > 0)
            {
                $name = $postdata["name"];
                $phone_number = $this->standardize_phone($postdata["phone_number"]);
                $email = $postdata["email"];
                $website = $postdata["website"];
                
                $sql = "INSERT INTO $contractortable (location_id, name, phone_number, email, website)
                    VALUES ('$newid', '$name', '$phone_number', '$email', '$website')";
                $result = mysql_query($sql);
                
                if($result)
                {
                    return "Successfully added new Contractor";
                }
                else
                {
                    return "There was a problem adding Contratcor Info.";
                }
            }
            else
            {
                return "There was a problem adding Map Data.";
            }
            // add Contractor data to DB
        }
        public function runQuery($zip, $email, $radius)
        {
            // validate input
            if(!$this->validateZip($zip))
            {
                return "Please enter a valid zip code (5 digit number)";
            }
            if(!$this->validateEmail($email))
            {
                return "$email is not a valid email";
            }
            
            // if we're good log the request
            $continue = $this->checkAndLogEmail($email);
            $zip = str_pad($zip, 5, 0, STR_PAD_LEFT);
            
            // once we have logged the request if the requester hasn't exceeded their request limit
            // we execute the request else we return the error message
            if($continue == 1)
            {
                // OK here's the real work
                $contractors = $this->executeRequest($zip, $radius);
                
                if(is_array($contractors))
                {
                    if($this->emailResults($email, $contractors))
                    {
                        return "success";
                    }
                    else
                    {
                        return "There was a problem with you request.";
                    }
                }
                else
                {
                    return $contractors;
                }
            }
            else
            {
                return $continue;
            }
        }
        
        public function executeRequest($zip, $radius)
        {
            $coords = $this->getZipCoords($zip);
            if(!is_array($coords))
            {
                return $coords;
            }
            
            $ziparray = $this->getZipsInRange(0, $radius, $zip);
            if(count($ziparray) > 0)
            {
                $zips = array();
                foreach($ziparray as $miles => $zipdata)
                {
                    $miles = round($miles, 3);
                    $zips[$zipdata["zip_code"]] = $miles;
                }
                
                // make sure the searched for zipcode is in the array
                $zips[$zip] = 0.0;
                $whereclausezips = "(" . implode(",", array_keys($zips)) . ")";
                $sql = "SELECT * 
                    FROM geo_contractors
                    JOIN geo_locations ON geo_contractors.location_id = geo_locations.id
                    WHERE zip_code IN $whereclausezips";
                
                $result = mysql_query($sql);

                if(mysql_num_rows($result) > 0)
                {
                    $contractors = array();
                    while($row = mysql_fetch_assoc($result))
                    {
                        // add miles data to the row
                        $row["miles"] = $zips[$row["zip_code"]];
                        $contractors[$row["id"]] = $row;
                    }
                    
                    // sort by miles
                    function cmp($a, $b)
                    {
                        
                        if ($a["miles"] == $b["miles"]) {
                            return 0;
                        }
                        elseif($a["miles"] < $b["miles"])
                        {
                            return -1;
                        }
                        elseif($b["miles"] < $a["miles"])
                        {
                            return 1;
                        }
                    }
                    // calls cmp function to compare miles
                    usort($contractors, "cmp");
                    //
                    // limit to three items
                    $contractors = array_splice($contractors, 0, 3);
                    return $contractors;
                   
                }
                else
                {
                    return "No Results Found";
                }
            }
            else
            {
                return "No results Found";
            }
        }
        
        
        
        private function emailContractor($contractor_email, $subject, $message, $headers)
        {
            // echo "Emailing $contractor_email...";
            // mail($contractor_email, $subject, $message, $headers);
        }
        
        private function checkForString($input, $pre = "", $post = "<br>")
        {
            if(strlen($input) > 0)
            {
                return $pre . $input . $post;
            }
            else  
            {
                return "";
            }
        }
        
        private function emailResults($email, $contractors)
        {
            $fromemail = $this->default_email_layout["from"];
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= "From: $fromemail" . "\r\n";
            $bcc = array();
            $message = "";
            $contractorslist = "";
            $contractor_count = count($contractors);
            
            if($this->default_email_layout["style"] != "")
            {
                $message .= "<style>" . $this->default_email_layout["style"] . "</style>";
            }
            else
            {
                $message .= "<style></style>";
            }
            
            if($this->default_email_layout["logo"] != "")
            {
                $message .= "<img src='" . $this->default_email_layout["logo"] . "' /><br>";
            }
            
            
            if($this->default_email_layout["title"] != "")
            {
                $message .= "<h2>" . $this->default_email_layout["title"] . "</h2>";
            }
            
            foreach($contractors as $id => $contractor)
            {	 	 	 	 	
                $contractorslist .= "<p>";
                $contractorslist .= $this->checkForString($contractor["name"]);
                $contractorslist .= $this->checkForString($contractor["street_address"]);

                $contractorslist .= $this->checkForString($contractor["city"], "", ", ");
                $contractorslist .= $this->checkForString($contractor["state"], "", " ");
                $contractorslist .= $this->checkForString($contractor["zip_code"]);

                $contractorslist .= $this->checkForString($contractor["phone_number"]);

                $contractorslist .= $this->checkForString($contractor["email"]);
                $contractorslist .= $this->checkForString($contractor["website"]);
                
                $addressstring = $contractor["street_address"] . " " . $contractor["city"] . ", " .$contractor["state"] . " " . $contractor["zip_code"];
                $google_directions_link = "http://maps.google.com/maps?daddr=" . $addressstring;
                $contractorslist .= "<a href='$google_directions_link'>Directions</a>";
                $contractorslist .= "</p>";
                
                // add contratcor email to BCC
                if(filter_var($contractor["email"], FILTER_VALIDATE_EMAIL))
                {
                    array_push($bcc, $contractor["email"]);
                }
            }
           // add bcc 
            if(!empty($this->default_email_layout["bcc"]))
            {
                array_push($bcc, $this->default_email_layout["bcc"]);
            }
            
            if(count($bcc) > 0)
            {
                $bccstring = implode(", ", $bcc);
                $headers .= "Bcc: $bccstring" . "\r\n";
            }
            
            // Message for requesting user
            if($this->default_email_layout["message_before_list"] != "")
            {
                $message .= "<p>" . $this->default_email_layout["message_before_list"] . "</p>";
            }
            else
            {
                $message .= "<p>We found $contractor_count contractor(s) in your search radius. <br>";
                $message .= "Here are the contractors near you: </p>";
            }
            $message .= $contractorslist;
            
            
            if($this->default_email_layout["message_after_list"] != "")
            {
                $message .=  "<p>" . $this->default_email_layout["message_after_list"] . "</p>";
            }
            
            if($this->default_email_layout["footer"] != "")
            {
                $message .= "<p>" . $this->default_email_layout["footer"] . "</p>";
            }
            
            $subject = $this->default_email_layout["subject"];
            // echo $message;
            return mail($email, $subject, $message, $headers);
        }
        
        private function checkAndLogEmail($email)
        {
            // get info about the requesting email
            $continue = false;
            
            $sql = "SELECT * FROM geo_request_log WHERE email = '$email' LIMIT 1";
            $result = mysql_query($sql);

            $rows = mysql_num_rows($result);
            
            
            // if there is no entry for this email create one
            if($rows < 1)
            {
               // returns true on successful insert
                return $this->addEmailToRequestLog($email);
            }
            else
            {
                // otherwise log the request
                while($row = mysql_fetch_assoc($result))
                {
                    $logResult = (string) $this->logRequest($row);
                    $request_count = $row["request_count"];
                    
                    if($logResult == "Reset and Continue")
                    {
                        return true;
                    }
                    // if email has made more than three request return error.
                    if($request_count > 2)
                    {
                         return "You have exceeded your request limit. You may make another request next month";
                    }
                    else
                    {
                        return true;
                    }
                }
            }
           
        }
        
        private function logRequest($row)
        {
            $request_count       = $row["request_count"];
            $request_count_total = $row["request_count_total"];
            $id                  = $row["id"];
            $last_request_month  = $row["last_request_month"];
            
            $new_request_count = $request_count + 1;
            $new_request_count_total = $request_count_total + 1;
            
            $current_month = date("F Y");
            
            // if it's a new month reset the request count and update the stored month
            if($current_month != $last_request_month)
            {
                $sql = "UPDATE geo_request_log 
                SET request_count = 1, request_count_total=$new_request_count_total, last_request_month = '$current_month'
                WHERE id = $id";
                $result = mysql_query($sql);
                
                return "Reset and Continue";
            }
            else
            {
                $sql = "UPDATE geo_request_log 
                SET request_count = $new_request_count, request_count_total=$new_request_count_total
                WHERE id = $id";
                $result = mysql_query($sql);
                
                return $result;
            }

            
        }
        
        private function addEmailToRequestLog($email)
        {
            $month = date("F Y");
            
            $sql = "INSERT INTO geo_request_log 
                (email, request_count, request_count_total, last_request_month)
                VALUES ('$email', 1, 1, '$month')";
            $result = mysql_query($sql);
            return $result;
        }
    }
?>
