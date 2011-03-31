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
    
    function populate_efficacy(){
        $mongo = new Mongo();
        $mdb = $mongo->p8statsdw;
        $efficacy = $mdb->efficacy;
            
        $rnd = $this->rnd;
        $db = $this->db;
        $dtb = $this->dim_tables;
        $ftb = $this->fact_tables;
        for($i=0;$i<1000000;$i++){
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
            
            $obj[extension] = $rnd->range(0,1);
            $obj[resubmission] = $rnd->range(0,1);
            $obj[ects] = $rnd->range(5,20);
            $obj[student]=1;
            
            $efficacy->insert($obj);
        }
    }
    
    function run(){
        $this->populate_efficacy();
    }
    
}

$obj = new PopulateDB();
$obj->run();
?>