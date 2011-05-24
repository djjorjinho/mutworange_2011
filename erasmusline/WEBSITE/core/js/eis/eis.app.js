/**
 * EIS Application class
 * 
 */
var eis = {
		
	rules : {},
	
	scenario : {},
	
	selectedItem : "",
	
	scenarioSelect : "",
	
	init : function(){
		$.blockUI();
		
		eis.loadDependencies();
		$.ajaxSetup({async: true});
		
		eis.loadLayout();
	},
	
	loadLayout : function(){
		
		jQuery(document).ready(function($) {
			
			eis.loadRules();
			eis.fillCubes();
			// color picker
			$('#colorSelector').ColorPicker({
				color: '#0000ff',
				onShow: function (colpkr) {
					$(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					$(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					$('#colorSelector div').css('backgroundColor', '#' + hex);
				}
			});
			
			// editable select box
			eis.scenarioSelect = 
				jQuery('#eis_toolbar select:first').editableSelect({
				case_sensitive: false,
				items_then_scroll: 10,
				onSelect : function(item){
					eis.getScenario(item.text());
				}
			});
			
			eis.getScenarios();
			
			$.unblockUI();
		});
	},
	
	loadDependencies : function(){
		
		$.ajaxSetup({async: false});
		
		jQuery.getScript('core/js/eis/jquery.json-2.2.min.js');
		jQuery.getScript('core/js/eis/jsonpath-0.8.0.min.js');
		jQuery.getScript('core/js/eis/jquery.tmpl.min.js');
		
		//jQuery.getScript('core/js/eis/accessibilityInspector.js');
		
		this.loadColorPicker();
		this.loadEditableSelect();
		
		// comment when done
		this.loadQunit();
	},
	
	loadEditableSelect : function(){
		var css_href = "core/js/editableselect/jquery.editable-select.css";
		var head = document.getElementsByTagName('head')[0]; 
		jQuery(document.createElement('link')).attr({type: 'text/css', 
				href: css_href, rel: 'stylesheet', 
		    	media: 'screen'}).appendTo(head);
		
		jQuery.getScript('core/js/editableselect/'+
				'jquery.editable-select.pack.js');
	},
	
	loadColorPicker : function(){
		var css_href = "core/js/colorpicker/colorpicker.css";
		var head = document.getElementsByTagName('head')[0]; 
		jQuery(document.createElement('link')).attr({type: 'text/css', 
				href: css_href, rel: 'stylesheet', 
		    	media: 'screen'}).appendTo(head);
		
		jQuery.getScript('core/js/colorpicker/colorpicker.js');
	},
	
	loadQunit : function(){
		var css_href = "core/js/eis/qunit/qunit.css";
		var head = document.getElementsByTagName('head')[0]; 
		jQuery(document.createElement('link')).attr({type: 'text/css', 
				href: css_href, rel: 'stylesheet', 
		    	media: 'screen'}).appendTo(head);
		
		jQuery.getScript('core/js/eis/qunit/qunit.js');
	},
	runTests : function(){
		jQuery("#qunit-testresult").show();
		jQuery.getScript('core/js/eis/test/test.js');
	},
	rpcCall : function(func,args,callSuccess,callError,async){
		jQuery.ajax({
			url: "modules/stats/rpc.php",
			type : "POST",
			data : {method : func, 
					params : jQuery.toJSON(args)},
			dataType : 'json',
			async : async,
			success: function(obj){
				if(obj.hasOwnProperty('error')){
					callError(obj['error']);
				}else{
					callSuccess(obj['result']);
				}
			},
			error : function (xhr, ajaxOptions, thrownError){
				callError({message:"CANT_CONNECT",exception:thrownError});
            }    
		});
	},
	
	loadRules : function(){

		eis.rpcCall("getRules",{},
				function(result){
					eis.rules = result;
	  			}, 
				function(error){
					console.log(error);
				},false);
	},
	
	fillCubes : function(){
		var cubes = eis.rules.cubes;
		
		var len = cubes.length;
		for(var i=0;i<len;i++){
			var cube = cubes[i];
			jQuery('#eis_option_tmpl').tmpl( 
					{value : cube.table, text : cube.description} 
					).appendTo('#eis_cube_container select');
			
		}
		
	},
	
	fillDimensionsAndMeasures : function(){
		$.blockUI();
		var table = jQuery('#eis_cube_container select option:selected').val();
		
		// resetting values
		eis.scenario.cube = table;
		eis.scenario.columns = [];
		eis.scenario.rows = [];
		eis.scenario.filters = {};
		eis.scenario.highlight = [];
		
		var cube = jsonPath(eis.rules, "$.cubes[?(@.table=='"+table+"')]")[0];
		
		eis.fillDimensions(table,cube);
		eis.fillMeasures(table,cube);
		$.unblockUI();
	},
	
	fillDimensions : function(table,cube){
		jQuery('#eis_dimensions ul').html('');
		if(table==""){
			return;
		} 
		
		var dimArr = cube.dimensions;
		var dimArrStr = dimArr.join("' || @.id=='"); 
		var expr = "$.dimensions[?(@.id=='"+dimArrStr+"')]";
		var dimensions = jsonPath(eis.rules, expr);
		var len = dimensions.length;
		
		for(var i=0;i<len;i++){
			var dimension = dimensions[i];
			var table = dimension.table;
			var pk = dimension.pk;
			var name = dimension.name;
			var levels = jQuery([]);
			
			
			if(dimension.hasAll){
				levels.push(jQuery('#eis_listitem_tmpl').tmpl( 
						{item : table+".all", text : "All"}
						));
			}
			
			var len2 = dimension.levels.length;
			for(var j=0;j<len2;j++){
				var column = dimension.levels[j].column;
				var cname = dimension.levels[j].name;
				
				levels.push(jQuery('#eis_listitem_tmpl').tmpl( 
						{item : table+"."+column , text : cname }
						));
			}
			
			var mainItem = jQuery('#eis_mainitem_tmpl').tmpl({text : name});
			
			levels.appendTo(mainItem.find('ul:first'));
			
			mainItem.appendTo('#eis_dimensions ul:first');
		}
		
		
	},
	
	fillMeasures : function(table,cube){
		jQuery('#eis_measures ul').html('');
		if(table==""){
			return;
		}
		
		var len = cube.measures.length;
		for(var i=0;i<len;i++){
			var measure = cube.measures[i];
			
			jQuery('#eis_listitem_tmpl').tmpl(
					{item : "measure."+measure.id , text : measure.name }
					).appendTo('#eis_measures ul:first');
			
		}
		
	},
	
	toggleShow : function(elm){
		jQuery(elm).parent().find('ul').toggle();
	},
	
	selectListItem : function(itm){
		eis.selectedItem = itm;
		console.log("selected: "+itm);
	},
	
	addToColumns : function(){
		if(eis.selectedItem == "") return;
		
		// TODO validation
		
		eis.scenario.columns.push(eis.selectedItem);
		
		eis.selectedItem = "";
	},
	
	addToRows : function(){
		if(eis.selectedItem == "") return;
		
		// TODO validation
		
		eis.scenario.rows.push(eis.selectedItem);
		
		eis.selectedItem = "";
	},
	
	getHighlightColor : function(){
		return $('#colorSelector div').css('backgroundColor');
	},
	
	paintScenario : function(){
		
	},
	
	getScenarios : function(){
		eis.scenarioSelect.find('option').remove();
		eis.rpcCall("getScenarioList",{
					user_id : _userid
				},
				function(result){

					var instance = eis.scenarioSelect.
									editableSelectInstances()[0];
					
					var len = result.length;
					for(var i=0;i<len;i++)
						instance.addOption(result[i].scenario_name);
					
	  			}, 
				function(error){
					console.log(error);
				},true);
	},
	
	getScenario : function(name){
		$.blockUI();
		eis.rpcCall("getScenarioConfig",{
			user_id : _userid,
			scenario_name : name
		},
		function(result){
				eis.scenario = result;
				jQuery('#eis_cube_container select').val(result.cube);
				eis.fillDimensionsAndMeasures();
				eis.paintScenario();
				eis.runScenario();
				$.unblockUI();
			}, 
		function(error){
			console.log(error);
			$.unblockUI();
		},true);
	},
	
	saveScenario : function(){
		$.blockUI();
		eis.scenario.scenario_name = jQuery(eis.scenarioSelect)
										.find(':selected').text();
		eis.scenario.user_id = _userid;
		eis.rpcCall("saveScenario",eis.scenario,
		function(result){
				
				$.unblockUI();
			}, 
		function(error){
			console.log(error);
			$.unblockUI();
		},true);
	},
	
	runScenario : function(){
		$.blockUI();
		eis.rpcCall("runScenario",eis.scenario,
		function(result){
				console.log(result);
				$.unblockUI();
			}, 
		function(error){
			console.log(error);
			$.unblockUI();
		},true);
	}
};


// main app cycle


eis.init();


