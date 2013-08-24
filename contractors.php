<?php
        include("class.ContractorManager.php");
        $CM = new ContractorManager();
        $_POST = $CM->sanitize($_POST);
                
        
        $CM->default_email_layout = array(
            "from" => "noreply@gulfeaglesupply.com", // goes in email from line - must be a valid email address
            
            "bcc" => "", // must be a valid email address
            
            "subject" => "Your Contractor Search Results", // goes in email subject line
            
            "style" => "h2{ color: #7D6A55; font-size: 26.4px} p{ margin: 0 0 20px 0;}", // for including simple styling
            
            "logo" => "http://gulfeaglesupply.com/img/logo.png", // absolute path to image DO NOT USE TAGS! Example: http://tester6.com/img/logo.png
           
            "title" => "", // Sort of a headline for the email
            
            "message_before_list" => "", // Content before the list of found contractors
            
            "message_after_list" => "", // Contenet for after our list of contractors
            
            "footer" => "http://www.GulfeagleSupply.com" // Use li tags or other means of separating content
        );
        // STYLE GUIDE 
        // 'title' is done with h2 tag
        // <p> tags separate sections
        // You may add tags in other parts of the message and style them as well
        
        $error = "";
        $zip = "";
        $email = "";
        $radius = "";
        $class = "";
        $hidden = "";
         if(isset($_POST["email"]))
        {
            if($_POST["email"] != "")
            {
                 $email = $_POST["email"];
            }
            else
            {
                $error = "Please enter an email";
            }
        }
        
         if(isset($_POST["radius"]))
        {
            if($_POST["radius"] != "")
            {
                 $radius = $_POST["radius"];
            }
            else
            {
                $error = "Please select a search radius.";
            }
        }
        
         if(isset($_POST["zipcode"]) )
        {
            if($_POST["zipcode"] != "")
                $zip = $_POST["zipcode"];
            else
            {
                $error = "Please enter a zip code.";
            }
        }
        
        $feedback = "";
        if(isset($_POST["email"]) && isset($_POST["email"]) && isset($_POST["radius"]) && $error == "")
        {
            $response = $CM->runQuery($zip, $email, $radius);
            if($response == "success")
            {
                $message = "We have emailed your results.";
                $hidden = "hidden";
                $class = "message";
            }
            else
            {
                $message = $response;
                $class = "alert";
            }
            
        }
        if($error != "")
        {
            $class = "alert";
        }
        $radusarray = array(
            5,
            15,
            50
        );
?>
    <div class="center">
        <div id="feedback-div">
            <div class="feedback <?php echo $class; ?>">
                <?php if(!empty($error)){ echo $error; }?>
                <?php if(!empty($message)){ echo $message; }?>
            </div>
        </div>
        <form action="contractors.php" method="post" class="contractor-form <?php echo $hidden; ?>" id="contractor-form">
            <table>
                <tr><td>Zip Code: </td><td><input type="text" name="zipcode" value="<?php echo $zip; ?>"></td></tr>
                <tr><td>Search Radius: </td>
                <td><select name="radius">
                    <option value="" selected="selected">Select Search Radius</option>
                    
                    <?php 
                        foreach($radusarray as $radius_choice)
                        {
                            $selected = "";
                            if($radius_choice == $radius)
                            {
                                $selected = "selected='selected'";
                            }
                            echo "<option $selected value='$radius_choice'>$radius_choice Miles</option>";
                        }
                    ?>
                </select></td></tr>
                <tr><td>Email Address:</td><td><input type="text" name="email" value="<?php echo $email; ?>"></td></tr>
                <tr><td colspan="2"><input type="submit" id="contractor-form-submit"></td></tr>
            </table>
        </form>
    </div>

    <script>
        $(document).ready(function(){
//            $("#contractor-form input").attr('disabled', "");
//            $("#contractor-form select").attr('disabled', "");
            
            $("#contractor-form-submit").click(function()
            {  
//                $("#contractor-form input").attr('disabled', "disabled");
//                $("#contractor-form select").attr('disabled', "disabled");
                $("#contractor-form").css("visibility", "hidden");
                $(".feedback").css("visibility", "hidden");
            });
        });
    </script>
    <link href="locationmanager/css/contractors.css" rel="stylesheet" type="text/css">
    
