#!/usr/bin/env php
<?php
$ipath = get_include_path();
$sep = DIRECTORY_SEPARATOR;
set_include_path($ipath.":".dirname(__FILE__)."${sep}..${sep}");

require_once 'lib/DB.php';
require_once 'lib/TSample.php';
require_once "lib/CsvToArray.Class.php";
class PopulateDB {
    
    var $dim_tables = array('dim_gender','dim_lodging','dim_mobility',
                            'dim_institution','dim_date','dim_phase',
                            'dim_study');
    
    var $fact_tables = array('fact_efficacy','fact_efficiency');
    
    var $ods_tables = array('ods_efficiency');
    var $meta_tables = array('meta_semester');

    var $dict_dir;
    
    var $rnd;
    var $db;
    
    function PopulateDB(){
        $this->db = DB::getInstance();
        $this->db->connect();
        $this->rnd = new TSample();
        $this->dict_dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'pop_dict';
    }
    
    function populate_gender(){
    	foreach (CsvToArray::open($this->dict_dir.DIRECTORY_SEPARATOR.
    						"gender.csv") as $R){
            $this->db->insert($R,$this->dim_tables[0]);
        }
    }
    
	function populate_lodging(){
    	foreach (CsvToArray::open($this->dict_dir.DIRECTORY_SEPARATOR.
    						"lodging.csv") as $R){
            $this->db->insert($R,$this->dim_tables[1]);
        }
    }
    
    function populate_mobility(){
    	foreach (CsvToArray::open($this->dict_dir.DIRECTORY_SEPARATOR.
    							"mobility.csv") as $R){
            $this->db->insert($R,$this->dim_tables[2]);
        }
    }
    
    function populate_institution(){
        foreach (CsvToArray::open($this->dict_dir.DIRECTORY_SEPARATOR.
        					"institution.csv") as $R){
            $this->db->insert($R,$this->dim_tables[3]);
        }
        
    }
    
    function populate_date1(){
    	$year='2009';
        $semester='1';
        

        $obj = array(
                    'year'=>$year,
                    'semester'=>$semester
                    );
        $this->db->insert($obj,$this->dim_tables[4]);
                
        $year='2009';
        $semester='2';
        

        $obj = array(
                    'year'=>$year,
                    'semester'=>$semester
                    );
        $this->db->insert($obj,$this->dim_tables[4]);
    	
    	
        $year='2010';
        $semester='1';
        

        $obj = array(
                    'year'=>$year,
                    'semester'=>$semester
                    );
        $this->db->insert($obj,$this->dim_tables[4]);
                
        $year='2010';
        $semester='2';
        

        $obj = array(
                    'year'=>$year,
                    'semester'=>$semester
                    );
        $this->db->insert($obj,$this->dim_tables[4]);
    }
    
    function populate_phase(){
        foreach (CsvToArray::open($this->dict_dir.DIRECTORY_SEPARATOR.
        					"phase.csv") as $R){
            $this->db->insert($R,$this->dim_tables[5]);
        }
    }
    
    function populate_study(){
        foreach (CsvToArray::open($this->dict_dir.DIRECTORY_SEPARATOR
        					."study.csv") as $R){
            $this->db->insert($R,$this->dim_tables[6]);
        }
    }
    
    function createDummyEfficiencyMRG(){
    	$db = $this->db;
		$efficiency_table = $this->fact_tables[1];
		$year = 2009;
		$semester = 2;
		
		// create a merging table based on template
        $mrg_table = $efficiency_table."_${year}_${semester}s";
        $db->execute("drop table if exists $mrg_table");
		$db->execute("create table $mrg_table like $efficiency_table");
        $db->execute("alter table $mrg_table engine=MyISAM");
    }
    
    function populate_efficacy($semester=1){
        $rnd = $this->rnd;
        $db = $this->db;
        $dtb = $this->dim_tables;
        $ftb = $this->fact_tables;
        
        // create a merging table based on template
        $mrg_table = $ftb[0]."_2010_${semester}s";
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
        $db->execute("alter table $ftb[0] UNION=(".
        									implode(",",$mrg_tables).")");
        try{
        // load indices to cache
        $db->execute("CACHE INDEX ".implode(",",$dtb)." IN hot_cache");
        $db->execute("LOAD INDEX INTO CACHE ".
        				implode(",",$dtb)." IGNORE LEAVES");
        $db->execute("LOAD INDEX INTO CACHE ".
        				implode(",",$mrg_tables)." IGNORE LEAVES");
        }catch(Exception $e){}
    }
    
    function populate_efficiency_ods(){
    	$rnd = $this->rnd;
    	$db = $this->db;
    	
    	$csv = CsvToArray::open($this->dict_dir.DIRECTORY_SEPARATOR.
        					"phase.csv");
    	
    	$dt1 = new DateTime('2011-05-02 10:00:00');
    	$dt2 = new DateTime('2011-05-02 10:00:00');
    	$dt2->add(new DateInterval("P10D"));
    	
        // one approved student
        $obj = array(
        	'student_id' => "PT-ISEP-1",
        	'institution_code' => 'isep',
        	'institution_host_code' => 'fkl',
        	'country_code' => 'pt',
        	'country_host_code' => 'de',
        	'year' => 2011,
        	'semester' => 2,
        	'dim_mobility_id' => 'study',
        	'dim_gender_id' => 'M',
        	'lodging_available' => 1
        );
        
    	foreach ($csv as $R){
    		
    		$dt1->add(new DateInterval("P10D"));
    		$dt2->add(new DateInterval("P10D"));
    		
    		$obj['create_date'] = $dt1->format('Y-m-d H:i:s');
    		$obj['approve_date'] = $dt2->format('Y-m-d H:i:s');
    		$obj['dim_phase_id'] = $R['dim_phase_id'];
    		
            $db->insert($obj,$this->ods_tables[0]);
        }
        
    	$dt1 = new DateTime('2011-05-05 10:00:00');
    	$dt2 = new DateTime('2011-05-05 10:00:00');
    	$dt2->add(new DateInterval("P10D"));
    	
        // another approved student
        $obj = array(
        	'student_id' => "PT-ISEP-2",
        	'institution_code' => 'isep',
        	'institution_host_code' => 'gent',
        	'country_code' => 'pt',
        	'country_host_code' => 'be',
        	'year' => 2011,
        	'semester' => 2,
        	'dim_mobility_id' => 'study',
        	'dim_gender_id' => 'M',
        	'lodging_available' => 1
        );
        
    	foreach ($csv as $R){
    		
    		$dt1->add(new DateInterval("P10D"));
    		$dt2->add(new DateInterval("P10D"));
    		
    		$obj['create_date'] = $dt1->format('Y-m-d H:i:s');
    		$obj['approve_date'] = $dt2->format('Y-m-d H:i:s');
    		$obj['dim_phase_id'] = $R['dim_phase_id'];
    		
            $db->insert($obj,$this->ods_tables[0]);
        }
    	
    	
        // one rejected student
    	$dt1 = new DateTime('2011-05-05 10:00:00');
    	$dt2 = new DateTime('2011-05-05 10:00:00');
    	$dt2->add(new DateInterval("P2D"));
    	

        $obj = array(
        	'student_id' => "PT-ISEP-3",
        	'institution_code' => 'isep',
        	'institution_host_code' => 'gun',
        	'country_code' => 'pt',
        	'country_host_code' => 'en',
        	'year' => 2011,
        	'semester' => 2,
        	'dim_mobility_id' => 'both',
        	'dim_gender_id' => 'F',
        	'lodging_available' => 1
        );
        
        $cnt=0;
        $reject=false;
    	foreach ($csv as $R){
    		$cnt++;
    		
    		$dt1->add(new DateInterval("P20D"));
    		$dt2->add(new DateInterval("P20D"));
    		
    		$obj['create_date'] = $dt1->format('Y-m-d H:i:s');
    		$obj['dim_phase_id'] = $R['dim_phase_id'];
    		
    		if($cnt>3){
    			$obj['reject_date'] = $dt2->format('Y-m-d H:i:s');
    			unset($obj['approve_date']);
    			$reject=true;
    		}else{
    			$obj['approve_date'] = $dt2->format('Y-m-d H:i:s');
    		}
    		
    		
            $db->insert($obj,$this->ods_tables[0]);
            if($reject) break;
        }
    	
        
        // another approved student
    	$dt1 = new DateTime('2011-05-15 10:00:00');
    	$dt2 = new DateTime('2011-05-15 10:00:00');
    	$dt2->add(new DateInterval("P10D"));
    	
        $obj = array(
        	'student_id' => "PT-ISEP-4",
        	'institution_code' => 'isep',
        	'institution_host_code' => 'gent',
        	'country_code' => 'pt',
        	'country_host_code' => 'be',
        	'year' => 2011,
        	'semester' => 2,
        	'dim_mobility_id' => 'study',
        	'dim_gender_id' => 'F',
        	'lodging_available' => 1
        );
        
    	foreach ($csv as $R){
    		
    		$dt1->add(new DateInterval("P10D"));
    		$dt2->add(new DateInterval("P10D"));
    		
    		$obj['create_date'] = $dt1->format('Y-m-d H:i:s');
    		$obj['approve_date'] = $dt2->format('Y-m-d H:i:s');
    		$obj['dim_phase_id'] = $R['dim_phase_id'];
    		
            $db->insert($obj,$this->ods_tables[0]);
        }
        
        
    // one rejected student
    	$dt1 = new DateTime('2011-05-17 10:00:00');
    	$dt2 = new DateTime('2011-05-17 10:00:00');
    	$dt2->add(new DateInterval("P2D"));
    	

        $obj = array(
        	'student_id' => "PT-ISEP-5",
        	'institution_code' => 'isep',
        	'institution_host_code' => 'gun',
        	'country_code' => 'pt',
        	'country_host_code' => 'en',
        	'year' => 2011,
        	'semester' => 2,
        	'dim_mobility_id' => 'both',
        	'dim_gender_id' => 'F',
        	'lodging_available' => 1
        );
        
        $cnt=0;
        $reject=false;
    	foreach ($csv as $R){
    		$cnt++;
    		
    		$dt1->add(new DateInterval("P10D"));
    		$dt2->add(new DateInterval("P15D"));
    		
    		$obj['create_date'] = $dt1->format('Y-m-d H:i:s');
    		$obj['dim_phase_id'] = $R['dim_phase_id'];
    		
    		if($cnt>2){
    			$obj['reject_date'] = $dt2->format('Y-m-d H:i:s');
    			unset($obj['approve_date']);
    			$reject=true;
    		}else{
    			$obj['approve_date'] = $dt2->format('Y-m-d H:i:s');
    		}
    		
    		
            $db->insert($obj,$this->ods_tables[0]);
            if($reject) break;
        }
        
        
        // one rejected student
        $dt1 = new DateTime('2011-05-05 10:00:00');
        $dt2 = new DateTime('2011-05-05 10:00:00');
        $dt2->add(new DateInterval("P2D"));
         
        
        $obj = array(
                	'student_id' => "PT-ISEP-6",
                	'institution_code' => 'isep',
                	'institution_host_code' => 'gun',
                	'country_code' => 'pt',
                	'country_host_code' => 'en',
                	'year' => 2011,
                	'semester' => 2,
                	'dim_mobility_id' => 'both',
                	'dim_gender_id' => 'F',
                	'lodging_available' => 0
        );
        
        $cnt=0;
        $reject=false;
        foreach ($csv as $R){
        	$cnt++;
        
        	$dt1->add(new DateInterval("P3D"));
        	$dt2->add(new DateInterval("P30D"));
        
        	$obj['create_date'] = $dt1->format('Y-m-d H:i:s');
        	$obj['dim_phase_id'] = $R['dim_phase_id'];
        
        	if($cnt>2){
        		$obj['reject_date'] = $dt2->format('Y-m-d H:i:s');
        		unset($obj['approve_date']);
        		$reject=true;
        	}else{
        		$obj['approve_date'] = $dt2->format('Y-m-d H:i:s');
        	}
        
        
        	$db->insert($obj,$this->ods_tables[0]);
        	if($reject) break;
        }
        
        // another approved student
        $dt1 = new DateTime('2011-04-25 10:00:00');
        $dt2 = new DateTime('2011-05-05 10:00:00');
        $dt2->add(new DateInterval("P13D"));
        
        $obj = array(
                	'student_id' => "PT-ISEP-7",
                	'institution_code' => 'isep',
                	'institution_host_code' => 'gent',
                	'country_code' => 'pt',
                	'country_host_code' => 'be',
                	'year' => 2011,
                	'semester' => 2,
                	'dim_mobility_id' => 'study',
                	'dim_gender_id' => 'M',
                	'lodging_available' => 1
        );
        
        foreach ($csv as $R){
        
        	$dt1->add(new DateInterval("P9D"));
        	$dt2->add(new DateInterval("P11D"));
        
        	$obj['create_date'] = $dt1->format('Y-m-d H:i:s');
        	$obj['approve_date'] = $dt2->format('Y-m-d H:i:s');
        	$obj['dim_phase_id'] = $R['dim_phase_id'];
        
        	$db->insert($obj,$this->ods_tables[0]);
        }
        
        
        // another approved student
        $dt1 = new DateTime('2011-05-11 10:00:00');
        $dt2 = new DateTime('2011-05-16 10:00:00');
        $dt2->add(new DateInterval("P10D"));
         
        $obj = array(
                	'student_id' => "PT-ISEP-8",
                	'institution_code' => 'isep',
                	'institution_host_code' => 'gent',
                	'country_code' => 'pt',
                	'country_host_code' => 'be',
                	'year' => 2011,
                	'semester' => 2,
                	'dim_mobility_id' => 'study',
                	'dim_gender_id' => 'F',
                	'lodging_available' => 0
        );
        
        foreach ($csv as $R){
        
        	$dt1->add(new DateInterval("P3D"));
        	$dt2->add(new DateInterval("P4D"));
        
        	$obj['create_date'] = $dt1->format('Y-m-d H:i:s');
        	$obj['approve_date'] = $dt2->format('Y-m-d H:i:s');
        	$obj['dim_phase_id'] = $R['dim_phase_id'];
        
        	$db->insert($obj,$this->ods_tables[0]);
        }
    }
    
    function checkHotCache(){
    	try{
		$db = $this->db;
		
		$C = $db->getOne("SELECT @@global.hot_cache.key_buffer_size".
							" as hot_cache");
		
		if($C['hot_cache']==0){
			$db->execute("SET GLOBAL hot_cache.key_buffer_size=402653184;");
		}
		}catch(Exception $e){}
	}
    
    function run(){
    	$this->checkHotCache();
    	
        // dimension tables
    	$this->populate_gender();
        $this->populate_mobility();
        $this->populate_lodging();
        $this->populate_institution();
        $this->populate_date1();
        $this->populate_phase();
        $this->populate_study();
        
        // fact tables
        $this->populate_efficacy(1);
        $this->populate_efficacy(2);
        
        // ODS
        $this->populate_efficiency_ods();
        //$this->createDummyEfficiencyMRG();
    }
    
}

$obj = new PopulateDB();
$obj->run();
?>