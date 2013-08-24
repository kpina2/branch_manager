<?php 
    include('../include/config.php'); 
    
    if(isset($_GET["manage"]))
    {
        if($_GET["manage"] == "contractors")
        {
            include("class.ContractorManager.php"); 
            $LM = new ContractorManager();           
            $postdata = $LM->sanitize($_POST);
        }
        elseif($_GET["manage"] == "branches")
        {
            include("class.BranchManager.php"); 
            $LM = new BranchManager();
            $postdata = $LM->sanitize($_POST);
        }
        
        $add_new_response = "";
        $upload_file_response = "";
        $export_file_response = "";
        if(isset($postdata["LM-action"]))
        {
            if($postdata["LM-action"] == "add-new")
            {
                $add_new_response = $LM->addLocation($postdata);
                if(strlen($add_new_response) > 0)
                {
                    $add_new_response = "<p class='action-feedback'>" . $add_new_response . "</p>";
                }
                
            }

            if($postdata["LM-action"] == "upload-list")
            {
                $upload_file_response = $LM->uploadList($postdata);
                if(strlen($upload_file_response) > 0)
                {
                    $upload_file_response = "<p class='action-feedback'>" . $upload_file_response . "</p>";
                }
            }

            if($postdata["LM-action"] == "export-list")
            {
                $export_file_response = $LM->exportData();
                if(strlen($export_file_response) > 0)
                {
                    $export_file_response = "<p class='action-feedback'>" . $export_file_response . "</p>";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]--><head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Gulf Eagle</title>
        <meta name="description" content="">
        
<?php 
    if($_SERVER[ 'HTTP_HOST' ] == "localhost:90")
    {
        include('tempheader.php');
    }
    else
    {
        include('../inc/header.php');
    }
?>
        <link rel="stylesheet" href="css/location-manager.css">
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
        <script type="text/javascript" src="js/jquery-validation/jquery.validate.js"></script>
        <script type="text/javascript" src="js/location-manager.js"></script>
        <h1>Manage Map Data</h1>
        <ul>
            <li><a href="../access_admin/menu.php">Main Admin Menu</a></li>
<!--            <br>
            <li><a href="index.php?manage=contractors">Contractors Data</a></li>
            <li><a href="index.php?manage=branches">Branches Data</a></li>
            
            <a href="../logoff.php">Log Out</a>-->
        </ul>
<?php
    $_SESSION["authenticated"] = true;
    if(isset($_GET["manage"]) && $_GET["manage"] == "contractors")
    {
        include("manage_contractors.php");
    }
    elseif(isset($_GET["manage"]) && $_GET["manage"] == "branches")
    {
        include("manage_branches.php");
    }
           
    if($_SERVER[ 'HTTP_HOST' ] == "localhost:90")
    {
        include('../inc/footer.php');
    }
    else
    {
        include('../inc/footer.php');
    }
?>