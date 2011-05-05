<?php

include("common.inc");


if (isset($_POST['queryString'])) {
    $queryString = $_POST['queryString'];
    if (strlen($queryString) > 0) {
        //$query = "select * from education";
          $query = "SELECT * FROM institutions inner join country on institutions.instCountry = country.Code
                    WHERE institutions.instName LIKE '$queryString%' LIMIT 10";
       
       $result = mysql_query($query);
        
        while ($row = mysql_fetch_array($result)) {
            echo '<li 
                onClick="fill3(\''.$row['instName'].'\',\''.$row['Name'].'\')
                ;">' . $row['instName'].'</li>';          
        }
             
    }
}
?>