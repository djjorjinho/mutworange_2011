<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function sendInfox($values, $table, $idInst) {
        $data = array(
            'table' => $table,
            'data' => $values
        );
        $jsonString = json_decode($data);
        InfoxController::Transfer($jsonString, $idInst);
        return true;
    }
    