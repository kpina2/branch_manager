<div>
    <h2>Manage Branch Locations</h2>
    <div class='manager-section'>  
        <h3>Upload a Branch List</h3>
        Use <a href="templates/Branch_Upload.csv">Upload Template</a> for your upload list. When using the template be sure to leave headers in place.
        <p><?php echo $upload_file_response; ?></p>
        <form enctype="multipart/form-data" method="post" action="index.php?manage=branches"id="upload-list-form">
            Select a file: <input type='file' accept='text/csv' name='upload_file' id='upload_file'>
            <input type='hidden' name='LM-action' value='upload-list'><br>
            <input type='button' value='Upload Branch List' id="upload-list-button" data-type=''>
        </form>
    </div>
    <div class='manager-section'>    
        <h3>Export All Branches</h3>
        <p><?php echo $export_file_response; ?></p>
        <form method="post" action="index.php?manage=branches" id="upload-list-form">
            <input type='hidden' name='LM-action' value='export-list'>
            <input type='submit' value='Export Branches'>
        </form>
        
    </div>
    <div class='manager-section'>
        <div class="location-manager-col">
            <h3>Add a Single Branch</h3>
            <p><?php echo $add_new_response; ?></p>
            <form method="post" action="index.php?manage=branches" id="add-new-form"></p>
                
                <p>Address*: <input type='text' name='Location[street_address]' id='street_address'></p>
                <p>City*: <input type='text' name='Location[city]' id='city'></p>
                <p>State*: <select name='Location[state]' id='state'>
                    <?php foreach(Location_helper::$states as $abbr => $state)
                    {
                        echo "<option value='$abbr'>" . $state . "</option>";
                    }
                    ?>
                </select></p>
                <p>Zip Code*: <input type='text' name='Location[zip_code]' id='zip_code'></p>
                <input type='hidden' name='Location[lat]' id='lat'>
                <input type='hidden' name='Location[lng]' id='lng'>
                
                <p>Phone Numbers: <textarea name='phone_numbers' id='phone_numbers'></textarea></p>
              
                <input type='hidden' name='LM-action' value='add-new'>
                
                <p>
                    <input type='button' value='Add Branch' id='add-location'>
                    <input type='submit' value='Verify and Submit' id='add-location-submit'>
                </p>
            </form>
        
        </div>
        <div class="location-manager-col">
            <div id="locations-map"></div>
            <div id="location-feedback"></div>
        </div>
        <div class="clear-cols"></div>
    </div>
</div>