<?php

class DB {

    protected $connect;
    protected $tbl;
    protected $db;
    protected $id;

    function DB() {
        include('../../../core/includes/config.php');
        $this->connect = mysql_connect(DB_HOST, DB_USER, DB_PASS);
        $this->db = DB_NAME;
        $this->id = 0;
        if ($this->connect)
            @mysql_select_db(DB_NAME, $this->connect);
    }

    public function checkTable($tbl) {
        $SQL = "SHOW TABLES";
        $rs = mysql_query($SQL);
        while ($row = mysql_fetch_row($rs))
            if ($row[0] == $tbl) {
                $this->tbl = $tbl;
                return true;
            }

        return false;
    }

    public function checkUserEntry($mail) {
        $SQL = "SELECT * FROM users WHERE email = '" . $mail . "'";
        $rs = mysql_query($SQL);

        $row = mysql_fetch_assoc($rs);
        if (empty($row))
            return true;
        else
            return false;
    }

    function insertData($key, $value) {
        $SQL = "INSERT INTO " . $this->tbl . " (" . $key . ") VALUES (" . $value . ")";
        mysql_query($SQL);
        $this->id = mysql_insert_id();
    }

    function updateUser($key, $value, $mail) {
        $array = explode(",", $key);
        $array2 = explode(",", $value);
        for ($i = 0; $i < count($array); $i++) {
            $c = count($array2[$i]);
            $array2[$i] = substr($array2[$i], 1, ($c - 2));
            $SQL = "UPDATE users SET " . $array[$i] . " = '" . $array2[$i] . "' WHERE email = '" . $mail . "'";
            mysql_query($SQL);
        }

        return true;
    }

    function checkInsert() {
        if ($this->id != 0)
            return true;
        else
            return false;
    }

    function checkUserExist($mail) {
        $SQL = "SELECT * FROM users WHERE email = '" . $mail . "'";
        $rs = mysql_query($SQL);
        $row = mysql_fetch_assoc($rs);

        if (empty($row))
            return null;
        else
            return $mail;
    }

    function checkRecordExists($key, $value, $table) {
        $SQL = "SELECT * FROM " . $table . " WHERE " . $key . " = '" . $value . "'";
        
        $rs = mysql_query($SQL);
        $array = mysql_fetch_array($rs);
       
            return $array;        
    }

    public function updateBelgium($table, $values, $emailField, $email) {
        // validate
        if (empty($values) || !is_array($values))
            throw new Exception('There are no values to update, or the values parameter is not an array');

        // build query, part 1: UPDATE $table SET 
        $query = 'UPDATE ' . $table . ' SET ';

        // build query, part 2: inject values
        $i = 0; // counter
        foreach ($values as $key => $value) {
            $query .= $key . ' = "' . $value . '"'; // add the value, escape it first though.
            if ($i != sizeof($values) - 1)
                $query .= ', ';   // add a comma
            $i++;
        }
        $query .=' WHERE ' . $emailField . " = '" . $email . "';";
        mysql_query($query);
    }

    public function insertBelgium($table, $values) {
        echo $table;
        // validate
        if (empty($values) || !is_array($values))
            throw new Exception('There are no values to insert, or the values parameter is not an array');

        // redefine vars
        $values = (array) $values;
        $table = (string) $table;

        // init vars
        $valuesKeys = array_keys($values);
        $valuesValues = array_values($values);

        // build query, part 1: INSERT INTO $table ($field1,$field2,...$fieldN) VALUES (
        $query = 'INSERT INTO ' . $table . ' (' . implode(', ', $valuesKeys) . ') VALUES (';

        // build query, part 2: inject values
        for ($i = 0; $i < sizeof($values); $i++) {
            $query .= '"' . $valuesValues[$i] . '"';  // add the value, escape it first though.
            if ($i != sizeof($values) - 1)
                $query .= ', ';    // add a comma
        }
        
        $query.=')';
        
         echo mysql_affected_rows(mysql_query($query));

        
    }

}

?>