<?php 
    include "credentials.php";
    
    $connection = new mysqli('localhost', $user, $pw, $db);
    
    $records = $connection->prepare("select * from scpdata ORDER BY subject + 0 asc");
    $records->execute();
    $result = $records->get_result();
?>