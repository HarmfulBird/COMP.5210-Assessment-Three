<div class="container mt-4" id="dataDisplay">
    <?php
      
        include "connection.php";
        
        if(isset($_POST['submit']))
        {
            // Write a prepare statemnet to insert data
            $insert = $connection->prepare("insert into scpdata(subject, class, containment, description, image) values(?,?,?,?,?)");
        
            $insert->bind_param("sssss",$_POST['subject'], $_POST['class'] ,$_POST['containment'], $_POST['description'], $_POST['image']);
            
            $existing = false;
            
            foreach($result as $existingSCP) {
                if($existingSCP['subject'] == $_POST['subject']) {
                    $existing = true;
                }
            }
            
            if($existing){
                echo "<div class='alert alert-success'>Record matches already existing record!<br>Please do not create duplicates.</div>";
            }
            else if($_POST['class'] == "") {
                echo "<div class='alert alert-success'>Class is invalid!<br>Please select an scp class from the list.</div>";
            }
            else if($insert->execute())
            {
                echo "<div class='alert alert-success'>Record added successfully!<br>Please head back to the home page to see your changes.</div>";
            }
            else
            {
                echo "<div class='alert alert-danger'>Error: {$insert->error}</div>";
            }
        }
        
    ?>
    <h1>Create a new record</h1>
    
    <p class="mt-1"><a href="index.php" class="back btn btn-dark">Back to home page.</a></p>
    
    <form method="post" action="index.php?page=create" class="form form-group" style="margin-top:20px; margin-bottom:50px;">
        <label>SCP #:</label>
        <br>
        <input type="text" name="subject" placeholder="Subject..." class="form-control" required>
        <br>
        
        <label>SCP Class:</label>
        <br>
        <select name="class" id="class" placeholder="Select SCP Class..." class="form-select">
            <option value="" disabled selected>Select SCP Class...</option>
            <option value="Safe">Safe</option>
            <option value="Euclid">Euclid</option>
            <option value="Keter">Keter</option>
        </select>
        <br>
        
        <label>SCP Containment Information:</label>
        <br>
        <textarea name="containment" class="form-control" placeholder="Enter Containment Info..." style="height:200px;" required></textarea>
        <br>
        
        <label>SCP Description:</label>
        <br>
        <textarea name="description" class="form-control" placeholder="Enter Description..." style="height:200px;" required></textarea>
        <br>
        
        <label>Image Path:</label>
        <br>
        <input type="text" name="image" class="form-control" placeholder="assests/scp-images/SCP-#.jpg">
        <br>
        
        <input type="submit" name="submit" class="btn btn-danger">
        
    </form>
</div>

