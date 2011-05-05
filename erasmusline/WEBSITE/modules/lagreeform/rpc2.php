<?php

include("common.inc");


if (isset($_POST['queryString'])) {
    $queryString = $_POST['queryString'];
    if (strlen($queryString) > 0) {
        //$query = "select * from education";
          $query = "SELECT * FROM users 
                    inner join institutions on users.institutionId = institutions.instId 
                    WHERE users.familyName LIKE '$queryString%' LIMIT 10";
       
       $result = mysql_query($query);
        
        while ($row = mysql_fetch_array($result)) {
            echo '<li 
                onClick="fill2(\''.$row['familyName']. ' ' . $row['firstName'] . '\',\''.$row['tel'].'\',\''.$row['email'].'\')
                ;">' . $row['familyName'] . ' ' . $row['firstName'] . ' [' . $row['email'] . ']</li>';          
        }
             
    }
}
?>
