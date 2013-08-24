<div>
     <h2>Manage 'Find a Roofer' Locations</h2>
    <div class='manager-section'>
        <h3>Upload a Contractor List</h3>
        Use <a href="templates/Contractor_Upload.csv">Upload Template</a> for your upload list. When using the template be sure to leave headers in place.
        <p><?php echo $upload_file_response; ?></p>
        <form enctype="multipart/form-data" method="post" action="index.php?manage=contractors"  id="upload-list-form">
            Select a file: <input type='file' accept='text/csv' name='upload_file' id='upload_file'>
            <input type='hidden' name='LM-action' value='upload-list'><br>
            <input type='button' value='Upload Contractor List' id="upload-list-button" data-type=''>
        </form>
    </div>
    <div class='manager-section'>
        <h3>Export All Contractors</h3>
        <p><?php echo $export_file_response; ?></p>
        <form method="post" action="index.php?manage=contractors" id="upload-list-form">
            <input type='hidden' name='LM-action' value='export-list'>
            <input type='submit' value='Export Contractors'>
        </form>
    </div>
    <div class='manager-section contents-floated'>
        <div class="location-manager-col">
            <h3>Add a Single Contractor</h3>
            <p><?php echo $add_new_response; ?></p>
            <form method="post" action="index.php?manage=contractors" id="add-new-form"></p>
                <p>Contractor Name*: <input type='text' name='name' id='name'></p>
                
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
                <input type='hidden' name='Location[lat]' id='lat' value="0">
                <input type='hidden' name='Location[lng]' id='lng' value="0">
                
                <p>Phone*: <input type='text' name='phone_number' id='phone_number'></p>
                <p>Email: <input type='text' name='email' id='email'></p>
                <p>Website: <input type='text' name='website' id='website'></p>

                <input type='hidden' name='LM-action' value='add-new'>
                <p>
                    <input type='button' value='Add Contractor' id='add-location'>
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