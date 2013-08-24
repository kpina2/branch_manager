<?php
    // This class is designed to get 
    require_once("class.dbconn.php");
    require_once("class.LocationManager.php");
    class BranchManager extends LocationManager
    {
        
        public $type = "branch";
        public $locationtable   = "geo_locations";
        public $branchtable   = "geo_branches";
        
        public $acceptableHeaders = array(
                "Street Address",
                "City",	
                "State",	
                "Zip Code",
                "Phone Numbers"
            );
        
        
        function uploadList($postdata) {
            $branch_list = parent::uploadList($postdata, $this->acceptableHeaders);
            if(is_array($branch_list))
            {
               return $this->process_list($branch_list);
            }
            else
            {
                return $branch_list;
            }
        }
        function process_list($branch_list)
        {
            if(count($branch_list) > 0)
            {
                $this->reset_branches();
                $branch_count = 0;
                foreach($branch_list as $index => $branch)
                {
                    $branch_count ++;
                    
                    $locationdata["Location"]["street_address"] = $branch["Street Address"];
                    $locationdata["Location"]["city"] = $branch["City"];
                    $locationdata["Location"]["state"] = $branch["State"];
                    $locationdata["Location"]["zip_code"] = $branch["Zip Code"];
                    $locationdata["Location"]["lat"] = $lat = 0.0;
                    $locationdata["Location"]["lng"] = $lng = 0.0;
                    
//                    TODO: Modify for branch inputs
                    $locationdata["phone_numbers"] = $branch["Phone Numbers"];
                  
                    $this->addLocation($locationdata);
                }
                return "Succesfully Loaded $branch_count ".$this->type."(s)";
            }
            else
            {
                return "Empty File";
            }
        }
        private function reset_branches()
        {
            $branchtable = $this->branchtable;
            $locationtable = $this->locationtable;
           
            $type = $this->type;
            
            $sql = "TRUNCATE TABLE $branchtable";
            
            $result = mysql_query($sql);
            
            $sql = "DELETE FROM $locationtable WHERE type = '$type'";
            $result = mysql_query($sql);
        }
        
        
         public function exportData()
        {
            $branchtable = $this->branchtable;
            $locationtable = $this->locationtable;
            
            $sql = "SELECT street_address, city, state, zip_code, phone_numbers
                FROM $branchtable
                JOIN $locationtable ON $branchtable.location_id = $locationtable.id";
            $result = mysql_query($sql);
            $filename = "Branch_Export";
            parent::exportData($result, $this->acceptableHeaders, $filename);
           
        }
        
        public function addLocation($postdata) {
            
            $newid = parent::addLocation($postdata["Location"], $this->type);
            $branchtable = $this->branchtable;
            if($newid > 0)
            {
                $phone_numbers = $postdata['phone_numbers'];
                $sql = "INSERT INTO $branchtable (location_id, phone_numbers)
                    VALUES ('$newid', '$phone_numbers')";
                $result = mysql_query($sql);

                if($result)
                {
                    return "Successfully added new Branch";
                }
                else
                {
                    return "There was a problem adding Branch Info.";
                }
            }
            else
            {
                return "There was a problem adding Map Data.";
            }
        }
        public function getBranchDetails($locationid)
        {
            $sql = "SELECT geo_locations.*, geo_branches.phone_numbers
                FROM geo_locations 
                JOIN geo_branches ON geo_branches.location_id = geo_locations.id
                WHERE geo_branches.location_id = $locationid";
        }
        public function getBranchesJSON()
        {
            $sql = "SELECT geo_locations.*, geo_branches.phone_numbers
                FROM geo_locations 
                JOIN geo_branches ON geo_branches.location_id = geo_locations.id";
            
            $result = mysql_query($sql);
         
            $branches = array();
            $branches_lookup = array();
            $branches_bystate = array();
            
            while($row = mysql_fetch_assoc($result))
            {
                $branches_bystate[$row["state"]][$row["id"]] = $row;
                
                $branches_lookup[$row["id"]]["id"] = $row["id"];
                $branches_lookup[$row["id"]]["street_address"] = $row["street_address"];
                $branches_lookup[$row["id"]]["city"] = $row["city"];
                $branches_lookup[$row["id"]]["state"] = $row["state"];
                $branches_lookup[$row["id"]]["zip_code"] = $row["zip_code"];
                $branches_lookup[$row["id"]]["lat"] = $row["lat"];
                $branches_lookup[$row["id"]]["lng"] = $row["lng"];
                $branches_lookup[$row["id"]]["phone_numbers"] = $row["phone_numbers"];
            }
            
            $branches["branches_bystate"] = json_encode($branches_bystate);
            $branches["branches_lookup"] = json_encode($branches_lookup);
            return $branches;
        }
        public function getBranchesByZip($postdata, $limit = 0)
        {
            $zip = $postdata["zipcode"];
            $radius = $postdata["radius"];
            $ziparray = $this->getZipsInRange(0, $radius, $zip);
            
            if(count($ziparray) > 0)
            {
                $zips = array();
                foreach($ziparray as $miles => $zipdata)
                {
                    $miles = round($miles, 2);
                    $zips[$zipdata["zip_code"]] = $miles;
                }
                $zips[$zip] = 0.0;
                $whereclausezips = "(" . implode(",", array_keys($zips)) . ")";
                $sql = "SELECT geo_locations.*, geo_branches.phone_numbers
                    FROM geo_branches
                    JOIN geo_locations ON geo_branches.location_id = geo_locations.id
                    WHERE zip_code IN $whereclausezips";
             
                $result = mysql_query($sql);

                if(mysql_num_rows($result) > 0)
                {
                    $branches = array();
                    while($row = mysql_fetch_assoc($result))
                    {
                        // add miles data to the row
                        $row["miles"] = $zips[$row["zip_code"]];
                        // use miles a key
                        $branches[$row["miles"]] = $row;
                    }
                    
                    // sort by miles
                    function cmp($a, $b)
                    {
                         return ($a["miles"] < $b["miles"]) ? -1 : 1;
                    }
                    // calls cmp function to compare miles
                    usort($branches, "cmp");
                    
                    
                    if($limit > 0)
                    {
                        // $branches = array_slice($branches, 0, $limit);
                    }
                    return $branches;
                }
            }
        }
        public function getStateData()
        {
            $sql = "SELECT DISTINCT geo_states.*, geo_state_imagemap.imagemap_data FROM geo_states 
                JOIN geo_state_imagemap ON geo_states.state_code = geo_state_imagemap.state_code
                JOIN geo_locations ON geo_locations.state = geo_states.state_code
                WHERE type = 'branch'";
                
            $result = mysql_query($sql);
            $state_data = array();
            $imagemap_data = array();
            
            while($row = mysql_fetch_assoc($result))
            {
                $state = new stdClass;
                $state->lat = $row["coords_lat"];
                $state->lng = $row["coords_lng"];
                $state->name = $row["state"];               
                $state_data[$row["state_code"]] = $state;
                
                $imagemap = new stdClass;
                $imagemap->imagemap_data =  $row["imagemap_data"];
                $imagemap->state = $row["state_code"];
                array_push($imagemap_data, $imagemap);
            }
            $states["state_data"] = $state_data;
            $states["imagemap_data"] = $imagemap_data;
            return $states;
        }

        // utility for getting initial data into 
        public function loadStateData()
        {
            return ""; // diabled
            $filename = "state.csv";
            $fh = fopen($filename, "r");
            
            while (!feof($fh)) {
                $line = fgets($fh);
                
                $fields = explode(",", $line);
                $state = trim($fields[0]);
                $lat = trim($fields[1]);
                $lng = trim($fields[2]);
                
                $sql = "UPDATE states SET coords_lat = '$lat', coords_lng = '$lng' WHERE state_code = '$state'";
                $result = mysql_query($sql);
            }
        }
    }

?>
