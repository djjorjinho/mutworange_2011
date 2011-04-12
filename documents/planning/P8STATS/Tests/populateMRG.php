#!/usr/bin/env php

<?php
require_once 'lib/DB.php';
require_once 'lib/TSample.php';
require_once "lib/CsvToArray.Class.php";
class PopulateDB {
    
    var $dim_tables = array('dim_gender','dim_lodging','dim_mobility',
                            'dim_institution','dim_date','dim_process',
                            'dim_study');
    
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
        $semester='1';
        $month='9';
        for($d=1;$d<32;$d++){
            $obj = array(
                         year=>$year,
                         month=>$month,
                         day=>$d,
                         semester=>$semester,
                         timestamp=>mktime(0,0,0,$month,$day,$year),
                         date => sprintf("%04d-%02d-%02d %02d:%02d:%02d",$year,$month,$day,0,0,0)
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
        $month='10';
        for($d=1;$d<31;$d++){
            $obj = array(
                         year=>$year,
                         month=>$month,
                         day=>$d,
                         semester=>$semester,
                         timestamp=>mktime(0,0,0,$month,$day,$year),
                         date => sprintf("%04d-%02d-%02d %02d:%02d:%02d",$year,$month,$day,0,0,0)
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
        $month='11';
        for($d=1;$d<32;$d++){
            $obj = array(
                         year=>$year,
                         month=>$month,
                         day=>$d,
                         semester=>$semester,
                         timestamp=>mktime(0,0,0,$month,$day,$year),
                         date => sprintf("%04d-%02d-%02d %02d:%02d:%02d",$year,$month,$day,0,0,0)
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
    }
    
    function populate_process(){
        foreach (CsvToArray::open($this->dict_dir."/process.csv") as $R){
            $this->db->insert($R,$this->dim_tables[5]);
        }
    }
    
    function populate_study(){
        foreach (CsvToArray::open($this->dict_dir."/study.csv") as $R){
            $this->db->insert($R,$this->dim_tables[6]);
        }
    }
    
    function populate_efficiency(){
        
    }
    
    function populate_efficacy(){
        $rnd = $this->rnd;
        $db = $this->db;
        $dtb = $this->dim_tables;
        $ftb = $this->fact_tables;
        $ftpl = $this->fact_tpl;
        
        // create a merging table based on template
        $mrg_table = $ftb[0]."_2011_2s";
        $db->execute("create table $mrg_table like $ftb[0]");
        $db->execute("alter table $mrg_table engine=MyISAM");
        $db->execute("alter table $mrg_table disable keys");
                
        for($i=0;$i<10000;$i++){
            $obj=array();
            $aux = $db->getRandom($dtb[0]);
            $obj['dim_gender_code'] = $aux['code'];
            
            $aux = $db->getRandom($dtb[1]);
            $obj['dim_lodging_code'] = $aux['code'];
            
            $aux = $db->getRandom($dtb[2]);
            $obj['dim_mobility_code'] = $aux['code'];
            
            $aux = $db->getRandom($dtb[3]);
            $obj['dim_home_institution_id'] = $aux['id'];
            
            $aux2 = $db->getRandom($dtb[3]);
            while($aux['id'] == $aux2['id']){
                $aux2 = $db->getRandom($dtb[3]);
            }
            $obj['dim_host_institution_id'] = $aux2['id'];
            
            $aux = $db->getRandom($dtb[4]);
            $obj['dim_date_id'] = $aux['id'];
            
            $aux = $db->getRandom($dtb[5]);
            $obj['dim_state_id'] = $aux['id'];
            
            $obj[extension] = $rnd->range(0,1);
            $obj[resubmission] = $rnd->range(0,1);
            $obj[ects] = $rnd->range(5,20);
            
            $db->insert($obj,$mrg_table);
        }
        
        // enable indices
        $db->execute("alter table $mrg_table enable keys");
        
        // uniting merging tables
        $mrg_tables = $db->getMergedTables($ftb[0]);
        $db->execute("alter table $ftb[0] UNION=(".implode(",",$mrg_tables).")");
        
        // load indices to cache
        $db->execute("CACHE INDEX ".implode(",",$dtb)." IN hot_cache");
        $db->execute("LOAD INDEX INTO CACHE ".implode(",",$dtb)." IGNORE LEAVES");
        $db->execute("LOAD INDEX INTO CACHE ".implode(",",$mrg_tables)." IGNORE LEAVES");
    }
    
    function run(){
        $this->populate_gender();
        $this->populate_mobility();
        $this->populate_lodging();
        $this->populate_institution();
        $this->populate_date();
        $this->populate_process();
        $this->populate_study();
        $this->populate_efficacy();
    }
    
}

$obj = new PopulateDB();
$obj->run();
?>