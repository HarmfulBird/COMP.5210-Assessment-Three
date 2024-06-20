<div class="container mt-4" id="dataDisplay">
    <div style="display: flex; flex-direction: column-reverse;">
        <?php
            // Enable error reporting
            error_reporting(E_ALL);
     
            // Display errors
            ini_set('display_errors', 1);
            
            include "connection.php";
            
            // initialise $row as empty array
            $row = [];
            
            //directed from index page record [update] button
            if(isset($_GET['update']))
            {
                $id = $_GET['update'];
                // based on id select appropriate record from db
                $recordID = $connection->prepare("select * from scpdata where id = ?");
                
                if(!$recordID)
                {
                    echo "<div class='alert alert-danger p-3 m-2'>Error preparing recored for updating.</div>";
                    exit;
                }
                
                $recordID->bind_param("i", $id);
                
                if($recordID->execute())
                {
                    echo "<div class='alert alert-info p-3 m-2'>Record ready for updating.</div>";
                    $temp = $recordID->get_result();
                    $row = $temp->fetch_assoc();
                }
                else
                {
                    echo "<div class='container alert alert-danger p-3 m-2'>Error: {$recordID->error}</div>";
                }
            }
            
            if(isset($_POST['submit']))
            {
                // Write a prepare statemnet to insert data
                $update = $connection->prepare("update scpdata set subject=?, class=?, description=?, containment=?, image=? where id=?");
            
                $update->bind_param("sssssi",$_POST['subject'], $_POST['class'], $_POST['description'], $_POST['containment'], $_POST['image'], $_POST['id']);
                
                if($update->execute())
                {
                    echo "<div class='alert alert-success p-3 m-2'>Record updated successfully!<br>Please refresh the page to see your changes.</div>";
                }
                else
                {
                    echo "<div class='alert alert-danger p-3 m-2'>Error: {$update->error}</div>";
                }
            }
        ?>
    </div>
      
    <h1 style="margin-top:20px;">Update record</h1>
    
    <p><a href="index.php" class="btn btn-dark">Back to index page.</a></p>
    
    <form id="updateForm" method="post" action="<?php echo "index.php?update=" .$id; ?>" class="form-group" style="margin-top:20px; margin-bottom:50px;">
        <input type="hidden" name="id" value="<?php echo isset($row['id']) ? $row['id'] : '' ; ?>">
        <input type="hidden" name="subject" value="<?php echo isset($row['subject']) ? $row['subject'] : '' ; ?>">
        <br>
        <label>SCP #:</label>
        <br>
        <input type="text" name="subject_display" placeholder="Subject..." class="form-control" value="<?php echo isset($row['subject']) ? $row['subject'] : '' ; ?>" disabled>
        <br>
        
        <label>SCP Class:</label>
        <br>
        <select name="class" id="class" placeholder="Select SCP Class..." class="form-select">
            <option <?php if(isset($row['class']) && $row['class'] == "Safe") echo "selected" ?> value="Safe">Safe</option>
            <option <?php if(isset($row['class']) && $row['class'] == "Euclid") echo "selected" ?> value="Euclid">Euclid</option>
            <option <?php if(isset($row['class']) && $row['class'] == "Keter") echo "selected" ?> value="Keter">Keter</option>
        </select>
        <br>
        
        <label>SCP Containment:</label>
        <br>
        <textarea name="containment" class="form-control" style="height:200px;"><?php echo isset($row['containment']) ? $row['containment'] : '' ; ?></textarea>
        <br>
        
        <label>SCP Description:</label>
        <br>
        <textarea name="description" class="form-control" style="height:200px;"><?php echo isset($row['description']) ? $row['description'] : '' ; ?></textarea>
        <br>
        
        <label>Image:</label>
        <br>
        <input type="text" name="image" placeholdoer="assests/scp-images/SCP-#.jpg" class="form-control"value="<?php echo isset($row['image']) ? $row['image'] : '' ; ?>">
        <br>
        
        <input type="submit" name="submit" value="Update Record" class="btn btn-danger">
        
    </form>
</div>