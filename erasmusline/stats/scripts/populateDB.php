#!/usr/bin/env php
<?php
$ipath = get_include_path();
set_include_path($ipath.":".dirname(__FILE__)."/../");

require_once 'lib/DB.php';
require_once 'lib/TSample.php';
require_once "lib/CsvToArray.Class.php";
class PopulateDB {
    
    var $dim_tables = array('dim_gender','dim_lodging','dim_mobility',
                            'dim_institution','dim_date','dim_phase',
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
    	foreach (CsvToArray::open($this->dict_dir."/gender.csv") as $R){
            $this->db->insert($R,$this->dim_tables[0]);
        }
    }
    
	function populate_lodging(){
    	foreach (CsvToArray::open($this->dict_dir."/lodging.csv") as $R){
            $this->db->insert($R,$this->dim_tables[1]);
        }
    }
    
    function populate_mobility(){
    	foreach (CsvToArray::open($this->dict_dir."/mobility.csv") as $R){
            $this->db->insert($R,$this->dim_tables[2]);
        }
    }
    
    function populate_institution(){
        foreach (CsvToArray::open($this->dict_dir."/institution.csv") as $R){
            $this->db->insert($R,$this->dim_tables[3]);
        }
        
    }
    
    function populate_date1(){
        $year='2011';
        $semester='1';
        
        $month='9';
        for($d=1;$d<31;$d++){
            $obj = array(
                         year=>$year,
                         semester=>$semester
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
        
        $month='10';
        for($d=1;$d<32;$d++){
            $obj = array(
                         year=>$year,
                         semester=>$semester
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
        
        $month='11';
        for($d=1;$d<31;$d++){
            $obj = array(
                         year=>$year,
                         semester=>$semester
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
        
    	$month='12';
        for($d=1;$d<32;$d++){
            $obj = array(
                         year=>$year,
                         semester=>$semester
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
        
    }
    
	function populate_date2(){
        $year='2011';
        $semester='2';
        
        $month='3';
        for($d=1;$d<32;$d++){
            $obj = array(
                         year=>$year,
                         semester=>$semester
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
        
        $month='4';
        for($d=1;$d<31;$d++){
            $obj = array(
                         year=>$year,
                         semester=>$semester
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
        
        $month='5';
        for($d=1;$d<32;$d++){
            $obj = array(
                         year=>$year,
                         semester=>$semester
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
        
		$month='6';
        for($d=1;$d<31;$d++){
            $obj = array(
                         year=>$year,
                         semester=>$semester
                         );
            $this->db->insert($obj,$this->dim_tables[4]);
        }
    }
    
    function populate_phase(){
        foreach (CsvToArray::open($this->dict_dir."/phase.csv") as $R){
            $this->db->insert($R,$this->dim_tables[5]);
        }
    }
    
    function populate_study(){
        foreach (CsvToArray::open($this->dict_dir."/study.csv") as $R){
            $this->db->insert($R,$this->dim_tables[6]);
        }
    }
    
    function populate_efficiency(){
        error_log("populate_efficiency not implemented yet");
    }
    
    function populate_efficacy($semester=1){
        $rnd = $this->rnd;
        $db = $this->db;
        $dtb = $this->dim_tables;
        $ftb = $this->fact_tables;
        $ftpl = $this->fact_tpl;
        
        // create a merging table based on template
        $mrg_table = $ftb[0]."_2011_${semester}s";
        $db->execute("create table $mrg_table like $ftb[0]");
        $db->execute("alter table $mrg_table engine=MyISAM");
        $db->execute("alter table $mrg_table disable keys");
                
        for($i=0;$i<1000;$i++){
            $obj=array();
            
            // date
            $aux = $db->getRandom($dtb[4],"semester=${semester}");
            $obj['dim_date_id'] = $aux['dim_date_id'];
            
            // gender
            $aux = $db->getRandom($dtb[0]);
            $obj['dim_gender_id'] = $aux['dim_gender_id'];
            
            // lodging
            $aux = $db->getRandom($dtb[1]);
            $obj['dim_lodging_id'] = $aux['dim_lodging_id'];
            
            // mobility
            $aux = $db->getRandom($dtb[2]);
            $obj['dim_mobility_id'] = $aux['dim_mobility_id'];
            
            // home institution
            $aux = $db->getRandom($dtb[3]);
            $obj['dim_institution_id'] = $aux['dim_institution_id'];
            
            // host institution
            $aux2 = $db->getRandom($dtb[3]);
            while($aux['dim_institution_id'] == $aux2['dim_institution_id']){
                $aux2 = $db->getRandom($dtb[3]);
            }
            $obj['dim_institution_host_id'] = $aux2['dim_institution_id'];
            
			// study
            $aux = $db->getRandom($dtb[6]);
            $obj['dim_study_id'] = $aux['dim_study_id'];
            
            // facts
            $obj['total_applications'] = $rnd->range(5,20);
            $obj['last_applications'] = $rnd->range(5,20);
            $obj['avg_ects'] = $rnd->range(10,15);
            $obj['max_ects'] = $rnd->range(16,20);
            $obj['min_ects'] = $rnd->range(6,9);
            
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
        // dimension tables
    	$this->populate_gender();
        $this->populate_mobility();
        $this->populate_lodging();
        $this->populate_institution();
        $this->populate_date1();
        $this->populate_date2();
        $this->populate_phase();
        $this->populate_study();
        
        // fact tables
        $this->populate_efficacy(1);
        $this->populate_efficacy(2);
        $this->populate_efficiency();
    }
    
}

$obj = new PopulateDB();
$obj->run();
?>