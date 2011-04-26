<?php

include("common.inc");


if (isset($_POST['queryString'])) {
    $queryString = $_POST['queryString'];
    if (strlen($queryString) > 0) {
        $query = "SELECT u.familyName,u.firstName,u.email,i.instName FROM users as u inner join institutions as i on u.institutionId = i.instId  WHERE familyName LIKE '$queryString%' && userId > 1 LIMIT 10";
        $result = mysql_query($query);
        
        while ($row = mysql_fetch_array($result)) {
            Plonk::dump($result);
            echo '<li 
                onClick="fill(\''.$row['familyName']. ' ' . $row['firstName'] . '\',\''.$row['instName'].'\')
                ;">' . $row['familyName'] . ' ' . $row['firstName'] . ' [' . $row['email'] . ']</li>';
           
        }
             
    }
}
?>