<?php

include("common.inc");


if (isset($_POST['queryString'])) {
    $queryString = $_POST['queryString'];
    if (strlen($queryString) > 0) {
          $query = "SELECT * FROM owner
                    WHERE familyName LIKE '$queryString%'";
       $result = mysql_query($query);
        while ($row = mysql_fetch_array($result)) {
            echo '<li 
                onClick="fill(\''.$row['familyName']. '\',\''.$row['firstName']. '\',\''.$row['email']. '\',\''.$row['streetNr']. '\',\''.$row['city']. '\',\''.$row['postalCode']. '\',\''.$row['tel']. '\',\''.$row['mobilePhone'].'\')
                ;">' . $row['familyName']. " " . $row['firstName'].'</li>';          
        }             
    }
}
?>