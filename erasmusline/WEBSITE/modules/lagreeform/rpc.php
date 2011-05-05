<?php

include("common.inc");


if (isset($_POST['queryString'])) {
    $queryString = $_POST['queryString'];
    $instName = $_POST['instName'];
    if (strlen($queryString) > 0) {
        //$query = "select * from education";
          $query = "SELECT * FROM education 
                    inner join educationPerInstitute on education.educationId = educationPerInstitute.studyId 
                    inner join institutions on educationPerInstitute.institutionId = institutions.instId 
                    WHERE education.educationName LIKE '$queryString%'
                    AND institutions.instName = '".$instName."' LIMIT 10";
       
       $result = mysql_query($query);
        
        while ($row = mysql_fetch_array($result)) {
            echo '<li 
                onClick="fill(\''.$row['educationName'].'\')
                ;">' . $row['educationName'].'</li>';          
        }
             
    }
}
?>