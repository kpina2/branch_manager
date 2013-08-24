    <script>
        js_state = '';
        js_byzip = '';
    </script>
    <?php 
            require_once 'class.BranchManager.php';
            $BM = new BranchManager();
            $areamap_states = $BM->getStateData();
            $branches = $BM->getBranchesJSON();
            
            $statepage = false;
            $searchpage = false;
            
            if(!empty($_POST))
            {
                $_POST = $BM->sanitize($_POST); 
            }
            
            $citystate = "";
            if(isset($_POST['citystate'])){$citystate = $_POST['citystate'];}
            
            $zip = "";
            if(isset($_POST['zipcode'])){$zip = $_POST['zipcode'];}
            
            $radius = 0;
            if(isset($_POST['radius'])){$radius = $_POST['radius'];}
            $radusarray = array(
                10,
                25,
                50,
                100
            );
            
            $searchmesage = "";
            $statelist = Location_helper::$states;
            if(isset($_GET['state']))
            {
                if(array_key_exists($_GET['state'], $statelist))
                {
                    $state_abbr = $_GET['state'];
                    $state_name = $statelist[$_GET['state']];
                    $statepage = true;
                    echo "<script>";
                        echo "js_state = '$state_abbr';";
                    echo "</script>";
                }
                else
                {
                   
                }
            }
            elseif(isset($_POST['zipcode']) || isset($_POST['citystate']))
            {
                
                if(!empty($zip) || !empty($citystate))
                {
                    if($radius > 0)
                    {
                        $searchpage = true;
                   
                        if(!empty($zip))
                        {
                            $response = $BM->getCityState($zip);
                            if($response["success"])
                            {
                                $citystate = $response['message'];
                            }
                        }
                        elseif(!empty($citystate))
                        {
                            if(str_replace(" ", "", $citystate) == "Daytona,FL"){$citystate = "Daytona Beach, FL";}
                            $response = $BM->getZip($citystate);
                            if($response["success"])
                            {
                                $zip = $response['message'];
                            }
                        }
                        
                        if(!$response["success"])
                        {
                            $searchmesage = "<p class='error_msg'>" . $response['message'] . "</p>";
                        }
                        else
                        {
                            $postdata["zipcode"] = str_pad($zip, 5, 0, STR_PAD_LEFT);
                            $postdata["radius"] = $radius;
                            
                            $branchesByZip = $BM->getBranchesByZip($postdata);
                            $centerpoint = $BM->getZipCoords($postdata["zipcode"]);
                            $response = array(
                                "list"          => $branchesByZip,
                                "centerpoint"   => $centerpoint
                            );
                            if(count($response["list"]) < 1)
                            {
                                $searchmesage = "<p class='error_msg'>No Results.</p>";
                            }
                            else
                            {
                                $responseJSON = json_encode($response);
                                echo "<script>";
                                    echo "js_byzip = $responseJSON;";
                                    echo "search_radius = $radius";
                                echo "</script>";
                            }
                        }
                    }
                    else
                    {
                        $searchmesage = "<p class='error_msg'>Please select a search radius.</p>";
                    }
                }
                else
                {
                    $searchmesage = "<p class='error_msg'>Please enter a Zip Code or City, State</p>";
                }   
            }
                 
    ?>
<!--   &callback=handleApiReady-->
    <?php if($statepage || $searchpage)
    {
        ?>

        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
        <script src="locationmanager/js/statelookup.js"></script>
        <script src="locationmanager/js/location-mapper.js?v=5"></script>

        <script>
            branchesByStateJSON = <?php echo $branches["branches_bystate"]; ?>;
            branch_lookup = <?php echo $branches["branches_lookup"]; ?>;
        </script>
    <?php } ?>
     <link href="locationmanager/css/branches.css?v=6" rel="stylesheet" type="text/css">
            <?php
           
            if($statepage)
            {   
               ?>
                <div class="col left-col">
                    <?php echo $state_name; ?>
                    <p id='map-feedback'></p>
                </div>
                <div class="col right-col">
                    <div id="location-map"></div>
                </div>
                <div class="clear-cols"></div>
            <?php
            }
            elseif($searchpage)
            {
                ?>
                    <h2>Search: </h2>
                    <div class='search-options'>
                        <form id="search-locations" method="post">
                            Zip Code: <input value="<?php echo $zip; ?>" type="text" name="zipcode" id="zipcode" autocomplete="off"><br>
                            City, State: <input value="<?php echo $citystate; ?>" type="text" name="citystate" id="citystate" autocomplete="off"><br>
                            <select name="radius" id="radius">
                            <option value="0" selected="selected" autocomplete="off">Select a Search Radius</option>
                            <?php 
                                foreach($radusarray as $radius_choice)
                                {
                                    $selected = "";
                                    if($radius == $radius_choice)
                                    {
                                        $selected = "selected=selected";
                                    }
                                    echo "<option $selected value='$radius_choice'>$radius_choice Miles</option>";
                                }
                            ?>
                            </select>
                            <input type="submit" name="search-locations-button" value="Search" id="search-button">
                        </form>
                    </div>
                    <?php echo $searchmesage; ?>
                    <div class="col left-col">
                    <p id='map-feedback'></p>
                    </div>
                    <div class="col right-col">
                        <div id="location-map"></div>
                    </div>
                    <div class="clear-cols"></div>
                <?php
            }
            else
            {
                ?>
                <h2>Search: </h2>
                
                    <div class='search-options'>
                        <form id="search-locations" method="post">
                            Zip Code: <input value="<?php echo $zip; ?>" type="text" name="zipcode" id="zipcode" autocomplete="off"><br>
                            City, State: <input value="<?php echo $citystate; ?>" type="text" name="citystate" id="citystate" autocomplete="off"><br>
                            <select name="radius" id="radius">
                            <option value="0" selected="selected" autocomplete="off">Select a Search Radius</option>
                            <?php 
                                foreach($radusarray as $radius_choice)
                                {
                                    $selected = "";
                                    if($radius == $radius_choice)
                                    {
                                        $selected = "selected=selected";
                                    }
                                    echo "<option $selected value='$radius_choice'>$radius_choice Miles</option>";
                                }
                            ?>
                            </select>
                            <input type="submit" name="search-locations-button" value="Search" id="search-button">
                        </form>
                    </div>
                <?php echo $searchmesage; ?>
                <hr>
                <h2>Click On State to View Branch Addresses: </h2>
                <div class="center">
                    <img alt="" src="img/Location-Map-2.gif" usemap="#FPMap0" height="501" width="769" />
                    <map id="FPMap0" name="FPMap0">
                        <?php
                            foreach($areamap_states["imagemap_data"] as $index => $data)
                            {
                                $coords = $data->imagemap_data;
                                $state  = $data->state;

                                if($coords != "")
                                {
        //                            echo "<area href='javascript:void(null);' shape='poly' coords='$coords' data-state='$state'>"; 
                                    echo "<area href='locations.php?state=$state' shape='poly' coords='$coords' data-state='$state'>";  

                                } 
                            }
                        ?>
                    </map>
                </div>
                <?php
            }
            ?> 