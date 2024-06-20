<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display JSON Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <div class="containerr">
        <header class="header">
            <!-- LOGO / Name -->
            <div class="logo" id="chk1">
                <img src="https://scpwiki.github.io/sigma/images/header-logo.svg" alt="Logo" aria-label="Website Logo">
                <div class="logo-names">
                    <h1>
                        <a href="index.php">
                            SCP Foundation
                        </a>
                    </h1>
                    <h2>
                        <a href="index.php">
                        Secure, Contain, Protect
                        </a>
                    </h2>
                </div>
            </div>
            <?php include "connection.php"; ?>
            
            <!-- Nav Menu -->
            <div class="center-nav .navli" id="chk2">
                <nav class="navbar navbar-dark">
                    <!-- hambuger menu for when the screen shrinks-->
                    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon bg-transparent"></span>
                    </button>
                    <div class="collapse navbar-collapse navli" id="navbarNavAltMarkup">
                        <ul class="navbar-nav  ms-auto ">
                            <li class="nav-item active">
                                <a href="index.php?page='create'" class="nav-link">Add New SCP Record</a>
                            </li>
                            <!-- using php loop through database and retrive subject values -->
                            <?php foreach($result as $link): ?>
                            <li>
                                <a href="index.php?link='<?php echo $link['subject']; ?>'" class="nav-link" >SCP- <?php echo $link['subject']; ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </nav> 
            </div>
        </header>
        
        
        <div id="dataDisplay">
            <?php 
        
                // Enable error reporting
                error_reporting(E_ALL);
            
                // Display errors
                ini_set('display_errors', 1);
                
                if(isset($_GET['page']) && trim($_GET['page'], "'") == 'create') {
                    include 'create.php';
                }
                if(isset($_GET['update'])) {
                    include 'update.php';
                }
                else if(isset($_GET['link'])) 
                {
                    // remove sigle quotes (%27 27%) from returned get value
                    //value to trim, character to trim out
                    $subject = trim($_GET['link'], "'");
        
                    // // run sql command to retrive record based on $model
                    // $record = $connection->query("select * from kenworth where model='$model'");
                   
                    // // save each field in record as an array
                    // $array = $record->fetch_assoc();
                   
                    //Prepared Statement
                    $statement = $connection->prepare("select * from scpdata where subject= ? ");
                    if(!$statement)
                    {
                        echo "<p>Error in preparing sql statement</p>";
                        exit;
                    }
                    // bind parameters takes 2 arguments the type of data and the var to bind to.
                    $statement->bind_param("s", $subject);
                   
                    if($statement->execute())
                    {
                        $get_result = $statement->get_result();
                        // check if record has been retrived
                        if($get_result->num_rows > 0)
                        {
                            $array = array_map('htmlspecialchars', $get_result->fetch_assoc());
                           
                            $update = "index.php?update=" .$array['id'];
                            $delete = "index.php?delete=" .$array['id'];
                            $image = "assets/scp-images/alt-image.png";
                           
                            if(!empty($array['image']) && file_exists($array['image']))
                            {
                                $image = "{$array['image']}";
                            }
                            echo "
                                <div class='content'>
                                    <div class='UD-Buttons'>
                                        <p class='buttons'><a href='{$update}' class='btn btn-dark'>Update Record</a> &nbsp;
                                        <a href='{$delete}' class='btn btn-danger'>Delete Record</a></p>
                                    </div>
                                    <div class='image'>
                                            <img src={$image} alt='{$array['subject']}'>
                                            <h1>Item #: {$array['subject']}</h1>
                                            <h1>Object Class: {$array['class']}</h1>
                                    </div>
                                    <div class='scp-info'>
                                        <!--Containment Info-->
                                        <div class='scp-containment-info'>
                                            <h3>Special Containment Procedures:</h3>
                                            
                                            <p>
                                                {$array['containment']}
                                            </p>
                                        </div>
                                        
                                        <hr>
                                        
                                        <!--SCP Desription-->
                                        <div class='scp-description'>
                                            <h3>Description:</h3>
                        
                                            <p>
                                                {$array['description']}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        else
                        {
                            echo "<p>No record found for subject:{$subject}</p>";
                        }
                    }
                    else
                    {
                        echo "<p>Error executing statement.</p>";
                    }
                }
                else if (!isset($_GET['page']) && !isset($_GET['update']))
                {
                    // this will display the first time a user visits the site
                    echo "
                        <div class='content-main'>
                            <div class='warning-home'>
                                <h1><span id='warn'>WARNING:</span> THE FOUNDATION DATABASE IS</h1>
                                <h1><span id='classified'>CLASSIFIED</span></h1>
                                <h2>UNAUTHORIZED PERSONNEL WILL BE TRACKED, LOCATED, AND DETAINED</h2>
                                <h1 id='scp'>SECURE. CONTAIN. PROTECT.</h1>
                            </div>
                        </div>
                    ";
                }
               
                // Delete funtionality
                if(isset($_GET['delete']))
                {
                   $deleteID = $_GET['delete'];
                   $delete_query = $connection->prepare("delete from scpdata where id = ?");
                   $delete_query->bind_param("i", $deleteID);
                   
                   if($delete_query->execute())
                   {
                       echo "<div class='container alert alert-danger mt-3' style='text-align: center;'>Record Deleted! &nbsp&nbspPlease refresh the page to see your changes.</div>";
                   }
                   else
                   {
                        echo "<div class='container alert alert-danger'>Error: {$delete_query->error}</div>";
                   }
                } // end of delete funtionality
            ?>
        </div>
        
    </div>
    
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js" crossorigin="anonymous"></script>
</body>
</html>