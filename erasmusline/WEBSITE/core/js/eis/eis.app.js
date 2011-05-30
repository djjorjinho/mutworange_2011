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
		
		this.loadDependencies();
		$.ajaxSetup({async: true});
		
		this.loadLayout();
	},
	
	loadLayout : function(){
		
		jQuery(document).ready(function($) {
			
			eis.loadRules();
			eis.fillCubes();
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
			
			eis.scenarioSelect = $('#eis_toolbar select:first').editableSelect({
				onSelect: function(list_item) {
					eis.getScenario(list_item.text());
				},
				case_sensitive: false,
				items_then_scroll: 10
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
		jQuery.getScript('core/js/eis/jquery.form.rpcpost.js');
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
	rpcCall : function(func,args,callSuccess,callError,async,url){
		if(url==undefined) url="modules/stats/rpc.php";
		jQuery.ajax({
			url: url,
			type : "POST",
			data : {method : func, 
					params : jQuery.toJSON(args)},
			dataType : 'json',
			async : async,
			success: function(_obj){
				if(_obj.hasOwnProperty('error')){
					callError(_obj.error);
				}else{
					callSuccess(_obj.result);
				}
			},
			error : function (xhr, ajaxOptions, thrownError){
				callError({message:"CANT_CONNECT",exception:thrownError});
            }    
		});
	},
	loadRules : function(){

		eis.rpcCall("getRules",{},
				function(rpcrules){
					eis.rules = rpcrules;
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
	
	fillDimensionsAndMeasures : function(reset){
		if(reset==undefined) reset=true;
		$.blockUI();
		var table = jQuery('#eis_cube_container select option:selected').val();
		
		// resetting values
		if(reset){
			eis.scenario.cube = table;
			eis.scenario.columns = [];
			eis.scenario.rows = [];
			eis.scenario.filters = {};
			eis.scenario.highlight = [];
		}
		var cube = jsonPath(eis.rules, "$.cubes[?(@.table=='"+table+"')]")[0];
		
		eis.fillDimensions(table,cube);
		eis.fillMeasures(table,cube);
		eis.paintScenario();
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
			//var pk = dimension.pk;
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
	
	selectListItem : function(itm,elm){
		eis.selectedItem = itm;
		var div = jQuery(elm).parents('div:first');
		var li = jQuery(elm).parent('li');
		div.find('li').removeClass('selected');
		jQuery(li).addClass('selected');
		
	},
	
	addToColumns : function(){
		if(eis.selectedItem == "") return;
		
		// TODO validation
		
		eis.scenario.columns.push(eis.selectedItem);
		
		eis.selectedItem = "";
		eis.paintScenario();
	},
	
	addToRows : function(){
		if(eis.selectedItem == "") return;
		
		// TODO validation
		
		eis.scenario.rows.push(eis.selectedItem);
		
		eis.selectedItem = "";
		eis.paintScenario();
	},
	
	RGBtoHEX : function(parts) {
		
		for (var i = 1; i <= 3; ++i) {
		    parts[i] = parseInt(parts[i]).toString(16);
		    if (parts[i].length == 1) parts[i] = '0' + parts[i];
		}
		var hexString = parts.join('');
		return '#'+hexString;
	},
	
	rgbToHsv : function (r, g, b) {
	    var r = (r / 255),  
	         g = (g / 255),  
	     b = (b / 255);   
	  
	    var min = Math.min(Math.min(r, g), b),  
	        max = Math.max(Math.max(r, g), b),  
	        delta = max - min;  
	  
	    var value = max,  
	        saturation,  
	        hue;  
	  
	    // Hue  
	    if (max == min) {  
	        hue = 0;  
	    } else if (max == r) {  
	        hue = (60 * ((g-b) / (max-min))) % 360;  
	    } else if (max == g) {  
	        hue = 60 * ((b-r) / (max-min)) + 120;  
	    } else if (max == b) {  
	        hue = 60 * ((r-g) / (max-min)) + 240;  
	    }  
	  
	    if (hue < 0) {  
	        hue += 360;  
	    }  
	  
	    // Saturation  
	    if (max == 0) {  
	        saturation = 0;  
	    } else {  
	        saturation = 1 - (min/max);  
	    }  
	  
	    return [Math.round(hue), Math.round(saturation * 100), 
	            Math.round(value * 100)];  
	},
	
	getHighlightColor : function(){
		var rgbString = $('#colorSelector div').css('background-color');
		var parts = rgbString.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/); 
		delete (parts[0]);
		
		
		// determine contrast text color
		var hsv = eis.rgbToHsv(parts[1],parts[2],parts[3]);
		var contrast = ((hsv[0] > 150 && hsv[2]>85) || hsv[2] < 50) ? 
				'#ffffff' : '#000000';
		//console.log(hsv);
		//console.log(contrast);
		
		return [eis.RGBtoHEX(parts),contrast]; 
	},
	
	paintScenario : function(){
		eis.paintScenarioAux('columns');
		eis.paintScenarioAux('rows');
		eis.paintScenarioAux('filters');
	},
	
	paintScenarioAux : function(tb){
		var regex1 = /^measure/;

		// columns
		var list = eis.scenario[tb];
		jQuery('#'+tb+'_list').html('');
		for(var i in list){
			var col = list[i];
			console.log(col);
			var parts = col.split('.');
			if(regex1.test(col)){
				var cube = eis.scenario.cube;
				var expr="$.cubes[?(@['table']=='"+cube+"')]"+
				".measures[?(@['id']=='"+parts[1]+"')]";
				var obj = jsonPath(eis.rules, expr)[0];
				jQuery('#eis_tbmes_tmpl').tmpl(
						{text:obj.name,mes:col,type:tb}
						).appendTo('#'+tb+'_list');
			}else{
				console.log(parts);
				var expr= (parts[1]=='all') ? 
						"$.dimensions[?(@['table']=='"+parts[0]+"')]"
						: "$.dimensions[?(@['table']=='"+parts[0]+"')]"+
							".levels[?(@['column']=='"+parts[1]+"')]";
				var obj = jsonPath(eis.rules, expr)[0];
				console.log(obj);
				jQuery('#eis_tbdim_tmpl').tmpl(
						{text:obj.name,dim:col,type:tb}
						).appendTo('#'+tb+'_list');
			}
		}
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
	
	setScenario : function(_result){
		$.blockUI();
		
		eis.scenario = _result;

		jQuery('#eis_cube_container option').attr('selected','');
		jQuery('#eis_cube_container option[value="'+_result.cube+'"]')
							.attr('selected','selected');
		eis.fillDimensionsAndMeasures(false);
		eis.paintScenario();
		eis.runScenario();
		eis.paintHlights(_result.highlight);
		$.unblockUI();
	},
	
	getScenario : function(name){
		$.blockUI();
		eis.rpcCall("getScenarioConfig",{
			user_id : _userid,
			scenario_name : name
		},
		eis.setScenario, 
		function(error){
			console.log(error);
			$.unblockUI();
		},false);
	},
	
	saveScenario : function(){
		$.blockUI();
		var instance = eis.scenarioSelect.editableSelectInstances()[0];
		eis.scenario.scenario_name = instance.current_value;
		eis.scenario.user_id = _userid;
		eis.scenario.string='';
		eis.rpcCall("saveScenario",eis.scenario,
		function(result){
				
				var exists = instance.select.find('option:contains('+
						instance.current_value+')').text();
				if(exists==""){
					instance.addOption(instance.current_value);
				}
				
				$.unblockUI();
			}, 
		function(error){
			console.log(error);
			$.unblockUI();
		},true);
	},
	
	runScenarioButton : function(){
		eis.runScenario();
		eis.paintHlights(eis.scenario.highlight);
	},
	
	runScenario : function(){
		$.blockUI();
		eis.rpcCall("runScenario",eis.scenario,
		function(result){
				eis.simpleHtmlTable(result);
				$.unblockUI();
			}, 
		function(error){
			console.log(error);
			$.unblockUI();
		},false);
	},
	
	newScenario : function(){
		$.blockUI();
		// resetting values
		eis.scenario.cube = "";
		eis.scenario.columns = [];
		eis.scenario.rows = [];
		eis.scenario.filters = {};
		eis.scenario.highlight = [];
		
		jQuery('#eis_cube_container option').attr('selected','');
		jQuery('#eis_cube_container select').val("");
		eis.fillDimensionsAndMeasures();
		eis.paintScenario();
		$.unblockUI();
	},
	
	swapColumnsRows : function(){
		$.blockUI();
		var aux = eis.scenario.columns;
		eis.scenario.columns = eis.scenario.rows;
		eis.scenario.rows = aux;
		
		eis.paintScenario();
		eis.runScenario();
		eis.paintHlights(eis.scenario.highlight);
		$.unblockUI();
	},
	
	exportScenario : function(){
		$.blockUI();
		$.postGo("modules/stats/export.php","runScenario",eis.scenario);
		$.unblockUI();
	},
	
	simpleHtmlTable : function(data){

		out="";
		out+="<table class='presentation_table' id='resultTable'>";
		    out+= "<thead>";
		    for (var item1 in data[0]) {
		        out+= "<td class='res_column'><b>"+item1+"<b></td>";
		    }
		    out+= "</thead>";
		    for (var row in data) {
		    	var cnt = eis.scenario.rows.length; cnt++;
		        out+= "<tr>";
		        for (var item2 in data[row]) {
		        	var cls = cnt > 0 ? 'res_row' : 'res_value'; 
		            out+= "<td class='"+cls+"'>"+data[row][item2]+"</td>";
		            cnt--;
		        }
		        out+= "</tr>";
		    }
		    out+= "</table>";
		    jQuery("#resultTableDiv").html(out);

	},
	
	
	
	addHlight : function(elm){
		$.blockUI();
		var colors = eis.getHighlightColor();

		var parent =jQuery(elm).parent();
		var _op = parent.find('select option:selected').val();
		var _value = parseInt(parent.find('input[type=text]').val());
		var item;
		
		if(_value != NaN){
			item = {op:_op,value:_value,color:colors[0],contrast:colors[1]};
			eis.scenario.highlight.push(item);
		}

		if($('#resultTable').length>0){
			eis.paintHlights([item]);
		}
		$.unblockUI();
	},
	
	paintHlights : function(items){
		
		for(var idx in items){
			var item = items[idx];
			var op = item.op;
			var value = item.value;
			var color = item.color;
			var contrast = item.contrast;
			
			$('#resultTable td.res_value').each(function(){
					var	val = parseInt($(this).text());
					
				   switch(op){
				   	case 'ge' : if(val >= value) 
				   				eis.paintElm(this,color,contrast);
				   		break;
				   	case 'gt' : if(val > value) 
		   							eis.paintElm(this,color,contrast);
			   			break;
				   	case 'eq' : if(val == value) 
							eis.paintElm(this,color,contrast);
				   		break;
				   	case 'lt' : if(val < value) 
						eis.paintElm(this,color,contrast);
			   			break;
				   	case 'le' : if(val <= value) 
						eis.paintElm(this,color,contrast);
			   			break;
				   }
				   
			});
			
		}

	},
	
	paintElm : function(elm,color,contrast){
		jQuery(elm).css('background-color',color).css('color',contrast);
	},
	
	resetHlight : function(){
		eis.scenario.highlight=[];
		var table = $('#resultTable');
		if(table.length>0){
			$(table).find('td.res_value').each(function(){
				eis.paintElm(this,'#ffffff','#000000');
			});
		}
	},
	
	showGraph : function(){
		
	},
	
	removeTBItem : function(elm,id,type){
		var list = eis.scenario[type];
		eis.scenario[type] = jQuery.grep(list,function(elm){
			return elm != id;
		});
		jQuery(elm).parent('span').remove();
	}
	
};


// main app cycle


eis.init();


