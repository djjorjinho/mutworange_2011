#!/usr/bin/env php
<?php
require_once 'lib/DB.php';
require_once 'lib/TSample.php';
require_once "lib/CsvToArray.Class.php";
class PopulateDB {
    
    var $dim_tables = array('dim_gender','dim_lodging','dim_mobility',
                            'dim_institution','dim_date','dim_state');
    
    var $fact_tables = array('fact_efficacy','fact_efficiency');
    
    var $dict_dir = 'pop_dict';
    
    var $rnd;
    var $db;
    
    function PopulateDB(){
        $this->db = DB::getInstance();
        $this->db->connect();
        $this->rnd = new TSample();
    }
    
    function populate_gender(){
        $obj = array(
                     code => 'M',
                     description => 'Male'
                     );
        $this->db->insert($obj,$this->dim_tables[0]);
        
        $obj = array(
                     code => 'F',
                     description => 'Female'
                     );
        $this->db->insert($obj,$this->dim_tables[0]);
    }
    
    function populate_mobility(){
        $obj = array(
                     code => 'study',
                     description => 'Study'
                     );
        $this->db->insert($obj,$this->dim_tables[2]);
        $obj = array(
                     code => 'intern',
                     description => 'Internship'
                     );
        $this->db->insert($obj,$this->dim_tables[2]);
        $obj = array(
                     code => 'both',
                     description => 'Study/Internship'
                     );
        $this->db->insert($obj,$this->dim_tables[2]);
    }
    
    function populate_lodging(){
        $obj = array(
                     code => 'campus',
                     description => 'Campus'
                     );
        $this->db->insert($obj,$this->dim_tables[1]);
        $obj = array(
                     code => 'house',
                     description => 'House'
                     );
        $this->db->insert($obj,$this->dim_tables[1]);
    }
    
    function populate_institution(){
        
        foreach (CsvToArray::open($this->dict_dir."/institution.csv") as $R){
            $this->db->insert($R,$this->dim_tables[3]);
        }
        
    }
    
    function populate_date(){
        $year='2011';
        $semester='2';
        $month='3';
        for($d=1;$d<32;$d++){
            $obj = array(
                         year=>$year,
                         month=>$month,
                         day=>$d,
                         semester=>$semester
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
        $month='4';
        for($d=1;$d<31;$d++){
            $obj = array(
                         year=>$year,
                         month=>$month,
                         day=>$d,
                         semester=>$semester
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
        $month='5';
        for($d=1;$d<32;$d++){
            $obj = array(
                         year=>$year,
                         month=>$month,
                         day=>$d,
                         semester=>$semester
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
    }
    
    function populate_state(){
        foreach (CsvToArray::open($this->dict_dir."/state.csv") as $R){
            $this->db->insert($R,$this->dim_tables[5]);
        }
    }
    
    function populate_efficiency(){
        
    }
    
    function populate_efficacy(){
        $rnd = $this->rnd;
        $db = $this->db;
        $dtb = $this->dim_tables;
        $ftb = $this->fact_tables;
        for($i=0;$i<5000000;){
            $obj=array();
            $aux = $db->getRandom($dtb[0]);
            $obj['dim_gender_code'] = $aux['code'];
            
            $aux = $db->getRandom($dtb[1]);
            $obj['dim_lodging_code'] = $aux['code'];
            
            $aux = $db->getRandom($dtb[2]);
            $obj['dim_mobility_code'] = $aux['code'];
            
            $aux = $db->getRandom($dtb[3]);
            $obj['dim_home_institution_id'] = $aux['id'];
            
            $aux = $db->getRandom($dtb[3]);
            $obj['dim_host_institution_id'] = $aux['id'];
            
            $aux = $db->getRandom($dtb[4]);
            $obj['dim_date_id'] = $aux['id'];
            
            $aux = $db->getRandom($dtb[5]);
            $obj['dim_state_code'] = $aux['code'];
            
            $obj[extension] = $rnd->range(0,1);
            $obj[resubmission] = $rnd->range(0,1);
            $obj[ects] = $rnd->range(5,20);
            
            $db->insert($obj,$ftb[0]);
        }
    }
    
    function run(){
        $this->populate_gender();
        $this->populate_mobility();
        $this->populate_lodging();
        $this->populate_institution();
        $this->populate_date();
        $this->populate_state();
        $this->populate_efficacy();
    }
    
}

$obj = new PopulateDB();
$obj->run();
?>