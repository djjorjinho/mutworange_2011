<?php

include("common.inc");


if (isset($_POST['queryString'])) {
    $queryString = $_POST['queryString'];
    $id = $_POST['id'];
    if (strlen($queryString) > 0) {
          $query = "SELECT * FROM coursesPerEducPerInst";
       
       $result = mysql_query($query);
        
        while ($row = mysql_fetch_array($result)) {
            echo '<li 
                onClick="fill('.$id.',\''.$row['courseName'].'\',\''.$row['courseCode'].'\','.$row['ectsCredits'].')
                ;">' . $row['courseName'].'</li>';          
        }
             
    }
}
?>