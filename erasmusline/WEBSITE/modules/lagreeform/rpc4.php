<?php

include("common.inc");


if (isset($_POST['queryString'])) {
    $queryString = $_POST['queryString'];
    $id = $_POST['id'];
    if (strlen($queryString) > 0) {
<<<<<<< HEAD
          $query = "SELECT * FROM coursesPerEducPerInst";
=======
          $query = "SELECT * FROM coursesPerEducPerInst WHERE courseName LIKE '$queryString%' LIMIT 10";
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
       
       $result = mysql_query($query);
        
        while ($row = mysql_fetch_array($result)) {
            echo '<li 
<<<<<<< HEAD
                onClick="fill('.$id.',\''.$row['courseName'].'\',\''.$row['courseCode'].'\','.$row['ectsCredits'].')
=======
                onClick="fill('.$id.',\''.$row['courseName'].'\',\''.$row['courseCode'].'\','.$row['ectsCredits'].');
>>>>>>> 4da1c74a776bcc0f2d661d5f6e565de49145ebfd
                ;">' . $row['courseName'].'</li>';          
        }
             
    }
}
?>