<?php
    include_once("class.BranchManager.php");
    $BM = new BranchManager();
    $postdata = $BM->sanitize($_POST);
    
    if($postdata["request_type"] == "updategeocode")
    {
        $BM->update_location_coords($postdata);
    }
    elseif($postdata["request_type"] == "getbranches")
    {
        $branches = $BM->getBranchesByZip($postdata, 1);
        $centerpoint = $BM->getZipCoords($postdata["zipcode"]);
        $response = array(
            "list"          => $branches,
            "centerpoint"   => $centerpoint
        );
        echo json_encode($response);
    }
    elseif($postdata["request_type"] == "getdetails")
    {
        // echo json_encode($BM->getBranchDetails($postdata["locationid"]));
    }
?>
