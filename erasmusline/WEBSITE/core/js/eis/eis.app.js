// log wrapper
window.log=function(){log.history=log.history||[];log.history.push(arguments);if(this.console){console.log(Array.prototype.slice.call(arguments))}};
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
		jQuery.blockUI();
		this.loadDependencies();
		
		jQuery.ajaxSetup({async: true});
		
		this.loadLayout();
	},
	
	loadLayout : function(){
		
		jQuery(document).ready(function() {
			
			eis.loadRules();
			eis.fillCubes();
			jQuery('#colorSelector').ColorPicker({
				color: '#0000ff',
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					jQuery('#colorSelector div').css('backgroundColor', '#' + hex);
				}
			});
			
			eis.scenarioSelect = jQuery('#eis_toolbar select:first')
				.editableSelect({
				onSelect: function(list_item) {
					eis.getScenario(list_item.text());
				},
				case_sensitive: false,
				items_then_scroll: 10
			});
			
			eis.getScenarios();
			
			jQuery.unblockUI();
		});
	},
	cleanTmpl : function(jqElm){
		return jqElm;
		return	jqElm.html(function(i,v){
			return v.replace("<\![CDATA[","").replace("]]>","");
			});
	},
	loadDependencies : function(){
		
		jQuery.ajaxSetup({async: false});

		jQuery.getScript('core/js/eis/jquery.json-2.2.min.js');
		jQuery.getScript('core/js/eis/jsonpath-0.8.0.min.js');
		jQuery.getScript('core/js/eis/jquery.tmpl.min.js');
		jQuery.getScript('core/js/eis/jquery.form.rpcpost.js');
		jQuery.getScript('core/js/eis/jquery.simplemodal.js');
		jQuery.getScript('https://www.google.com/jsapi');

		jQuery.getScript('https://www.google.com/uds/api/visualization/1.0/c044e0de584c55447c5597e76d372bc1/default,corechart.I.js');
		// jQuery.getScript('core/js/eis/accessibilityInspector.js');
		
		eis.loadColorPicker();
		eis.loadEditableSelect();
		eis.loadJAlert();
		
		// comment when done
		//eis.loadQunit();
	},
	
	loadJAlert : function(){
		var css_href = "core/js/jalert/jquery.alerts.css";
		var head = document.getElementsByTagName('head')[0]; 
		jQuery(document.createElement('link')).attr({type: 'text/css', 
				href: css_href, rel: 'stylesheet', 
		    	media: 'screen'}).appendTo(head);
		
		jQuery.getScript('core/js/jalert/'+
				'jquery.alerts.js');
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
					log(error);
				},false);
	},
	
	fillCubes : function(){
		var cubes = eis.rules.cubes;
		
		var len = cubes!=undefined ? cubes.length : 0;
		for(var i=0;i<len;i++){
			var cube = cubes[i];
			jQuery('#eis_option_tmpl').tmpl( 
					{value : cube.table, text : cube.description} 
					).appendTo('#eis_cube_container select');
			
		}
		
	},
	
	fillDimensionsAndMeasures : function(reset){
		if(reset==undefined) reset=true;
		jQuery.blockUI();
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
		jQuery.unblockUI();
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
			// var pk = dimension.pk;
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
			
			var mainItem = jQuery('#eis_mainitem_tmpl')
				.tmpl({text : name});
			
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
	
	itemExists : function(){
		if(eis.selectedItem == "") return true;
		
		if(jQuery.inArray(eis.selectedItem,eis.scenario.columns)!=-1 || 
			jQuery.inArray(eis.selectedItem,eis.scenario.rows)!=-1
		){
			return true;
		}
		return false;
	},
	
	clearSelection : function(){
		eis.selectedItem = "";
		jQuery('#eis_dimensions li').removeClass('selected');
		jQuery('#eis_measures li').removeClass('selected');
	},
	
	addToColumns : function(){
		if(eis.selectedItem == "") return;
		
		if(eis.itemExists()){
			eis.clearSelection();
			return;
		} 
		
		eis.scenario.columns.push(eis.selectedItem);
		
		eis.clearSelection();
		
		eis.paintScenario();
	},
	
	addToRows : function(){
		if(eis.selectedItem == "") return;
		
		if(eis.itemExists()){
			eis.clearSelection();
			return;
		} 
		
		eis.scenario.rows.push(eis.selectedItem);
		
		eis.clearSelection();
		
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
		var rgbString = jQuery('#colorSelector div').css('background-color');
		var parts = rgbString.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/); 
		delete (parts[0]);
		
		
		// determine contrast text color
		var hsv = eis.rgbToHsv(parts[1],parts[2],parts[3]);
		var contrast = ((hsv[0] > 150 && hsv[2]>85) || hsv[2] < 50) ? 
				'#ffffff' : '#000000';
		// log(hsv);
		// log(contrast);
		
		return [eis.RGBtoHEX(parts),contrast]; 
	},
	
	paintScenario : function(){
		eis.paintScenarioAux('columns');
		eis.paintScenarioAux('rows');
		eis.paintFilters();
	},
	
	paintScenarioAux : function(tb){
		var regex1 = /^measure/;

		// columns
		var list = eis.scenario[tb];
		jQuery('#'+tb+'_list').html('');
		for(var i in list){
			var col = list[i];
			// log(col);
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
				// log(parts);
				var expr= (parts[1]=='all') ? 
						"$.dimensions[?(@['table']=='"+parts[0]+"')]"
						: "$.dimensions[?(@['table']=='"+parts[0]+"')]"+
							".levels[?(@['column']=='"+parts[1]+"')]";
				var obj = jsonPath(eis.rules, expr)[0];
				// log(obj);
				(parts[1]=='all') ?  
						jQuery('#eis_tbmes_tmpl').tmpl(
								{text:obj.name,mes:col,type:tb}
								).appendTo('#'+tb+'_list')
				: jQuery('#eis_tbdim_tmpl').tmpl(
						{text:obj.name,dim:col,type:tb}
						).appendTo('#'+tb+'_list');
			}
		}
	},
	
	paintFilters : function(){
		delete eis.scenario.filters['_hash'];
		
		var filterList = jQuery('#filters_list');
		filterList.html('');
		var filters = eis.scenario.filters;
		for(var i in filters){
			var field = filters[i];
			var parts = i.split('.');
			var expr = "$.dimensions[?(@['table']=='"+parts[0]+"')]"+
			".levels[?(@['column']=='"+parts[1]+"')]";
			var obj = jsonPath(eis.rules, expr)[0];
			
			jQuery('#eis_tbfil_tmpl').tmpl({text:obj.name,field:i})
				.appendTo(filterList);
			
		}
		
		eis.scenario.filters['_hash']=true;
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
					log(error);
				},true);
	},
	
	setScenario : function(_result){
		jQuery.blockUI();
		
		eis.scenario = _result;

		jQuery('#eis_cube_container option').attr('selected','');
		jQuery('#eis_cube_container option[value="'+_result.cube+'"]')
							.attr('selected','selected');
		eis.fillDimensionsAndMeasures(false);
		eis.paintScenario();
		eis.runScenario();
		eis.paintHlights(_result.highlight);
		jQuery.unblockUI();
	},
	
	getScenario : function(name){
		jQuery.blockUI();
		eis.rpcCall("getScenarioConfig",{
			user_id : _userid,
			scenario_name : name
		},
		eis.setScenario, 
		function(error){
			log(error);
			jQuery.unblockUI();
		},false);
	},
	
	saveScenario : function(){
		if(!eis.validScenario()) return;
		jQuery.blockUI();
		var instance = eis.scenarioSelect.editableSelectInstances()[0];
		eis.scenario.scenario_name = instance.current_value;
		eis.scenario.user_id = _userid;
		eis.scenario.string='';
		eis.scenario.filters['_hash']=true;
		eis.rpcCall("saveScenario",eis.scenario,
		function(result){
				
				var exists = instance.select.find('option:contains('+
						instance.current_value+')').text();
				if(exists==""){
					instance.addOption(instance.current_value);
				}
				
				jQuery.unblockUI();
			}, 
		function(error){
			log(error);
			jQuery.unblockUI();
		},true);
		delete eis.scenario.filters['_hash'];
	},
	
	runScenarioButton : function(){
		eis.runScenario();
		eis.paintHlights(eis.scenario.highlight);
	},
	
	runScenario : function(){
		if(!eis.validScenario()) return;
		jQuery.blockUI();
		delete eis.scenario.filters['_hash'];
		eis.rpcCall("runScenario",eis.scenario,
		function(result){
				eis.simpleHtmlTable(result);
				jQuery.unblockUI();
			}, 
		function(error){
			log(error);
			jQuery.unblockUI();
		},false);
		
		eis.scenario.filters['_hash']=true;
	},
	
	newScenario : function(){
		jQuery.blockUI();
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
		jQuery.unblockUI();
	},
	
	swapColumnsRows : function(){
		if(!eis.validScenario()) return;
		jQuery.blockUI();
		var aux = eis.scenario.columns;
		eis.scenario.columns = eis.scenario.rows;
		eis.scenario.rows = aux;
		
		eis.paintScenario();
		eis.runScenario();
		eis.paintHlights(eis.scenario.highlight);
		jQuery.unblockUI();
	},
	
	exportScenario : function(){
		if(!eis.validScenario()) return;
		jQuery.blockUI();
		jQuery.postGo("modules/stats/export.php","runScenario",eis.scenario);
		jQuery.unblockUI();
	},
	
	simpleHtmlTable : function(data){
		var regex = /^measure/;
		var drows = jQuery.grep(eis.scenario.rows,function(elm){
			return !regex.test(elm);
		});
		out="";
		out+="<table class='presentation_table' id='resultTable'>";
		    out+= "<thead>";
		    for (var item1 in data[0]) {
		        out+= "<td class='res_column'><b>"+item1+"<b></td>";
		    }
		    out+= "</thead>";
		    for (var row in data) {
		    	var cnt = drows.length; // cnt++;
		        out+= "<tr>";
		        for (var item2 in data[row]) {
		        	var cls = cnt > 0 ? 'res_row' : 'res_value'; 
		        	var val = data[row][item2]
		        	val= (val==undefined) ? '': val;
		        	// log(val);
		            out+= "<td class='"+cls+"'>"+val+"</td>";
		            cnt--;
		        }
		        out+= "</tr>";
		    }
		    out+= "</table>";
		    jQuery("#resultTableDiv").html(out);

	},
	
	
	
	addHlight : function(elm){
		jQuery.blockUI();
		var colors = eis.getHighlightColor();

		var parent =jQuery(elm).parent();
		var _op = parent.find('select option:selected').val();
		var _value = parseInt(parent.find('input[type=text]').val());
		var item;
		
		if(_value != NaN){
			item = {op:_op,value:_value,color:colors[0],contrast:colors[1]};
			eis.scenario.highlight.push(item);
		}

		if(jQuery('#resultTable').length>0){
			eis.paintHlights([item]);
		}
		jQuery.unblockUI();
	},
	
	paintHlights : function(items){
		
		for(var idx in items){
			var item = items[idx];
			var op = item.op;
			var value = item.value;
			var color = item.color;
			var contrast = item.contrast;
			
			jQuery('#resultTable td.res_value').each(function(){
					var	val = parseInt(jQuery(this).text());
					
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
		var table = jQuery('#resultTable');
		if(table.length>0){
			jQuery(table).find('td.res_value').each(function(){
				eis.paintElm(this,'#ffffff','#000000');
			});
		}
	},
	
	
	validScenario: function(){
		if(eis.scenario==null){
			return false;
		}
		
		if(!eis.scenario.hasOwnProperty('columns')){
			return false;
		}
		
		if(eis.scenario.columns.length == 0){
			jAlert("Please select one Dimension for the columns");
			return false;
		}
		
		if(!eis.scenario.hasOwnProperty('rows')){
			return false;
		}
		
		if(eis.scenario.rows.length == 0){
			jAlert("Please select one Dimension for the rows");
			return false;
		}
	
		var regex = /^measure/;
		var dims = jQuery.grep(eis.scenario.columns,function(elm){
			return !regex.test(elm);
		});
		if(dims.length == 0){
			jAlert("Please select one Dimension for the columns");
			return false;
		}
		
		dims = jQuery.grep(eis.scenario.rows,function(elm){

			return !regex.test(elm);
		});
		if(dims.length == 0){
			jAlert("Please select one Dimension for the rows");
			return false;
		}
			
		
		return true;
	},
	
	
	showGraph : function(){
		
		if(!eis.validScenario()) return;
		
		jQuery.blockUI();
		delete eis.scenario.filters['_hash'];
		
		$('#chart_div').modal();
		
		var type = document.getElementById('chart_select').value;		
		
		eis.rpcCall("runScenario",eis.scenario,
				function(result){
					eis.showGraph2(result, type);
				},
				function(error){},
				true		
			);
		
		eis.scenario.filters['_hash']=true;
		
		jQuery.unblockUI();	
	},
	
	
	
	showGraph2 : function(data, type){

			var dt = [];
			var regex = /^measure/;
			var measure_list = jQuery.grep(eis.scenario.rows
					,function(elm){
				return regex.test(elm);
			});
			var measure_count=measure_list.length;
			
			if(type == 'bars' || type == 'line'){
				
				var temp_dt2=[];
				var cnt = eis.scenario.rows.length-measure_count;
	
				temp_dt2.push('Column');
				for (var item2 in data[0]) {
	  				
					if(cnt < 1){
				        	temp_dt2.push(item2);
					}
		            cnt--;
		        } 
				
				dt.push(temp_dt2);
				
				
			    for (var row in data) {
			    	var cnt = eis.scenario.rows.length-measure_count;
			    	log(eis.scenario);
			    	temp_dt=[];
			    	
			        	
			        	var condition = true;
			        	var string = "";
			        	for (var item2 in data[row]) {
			        		
			        		if(cnt < 1 ){
			        			if(condition){
			        				temp_dt.push(string);
			        				condition=false;
			        			}
				        		if(data[row][item2] == undefined){
				        			temp_dt.push(0);
				        		}else{
					        		temp_dt.push(parseInt(data[row][item2]));
				        		}
				        	}
				        	else{
					        	string += data[row][item2]+" | ";
				        	}
				        	
				            cnt--;
				        }
			        
			        
			        dt.push(temp_dt);
	
			    }
			    
			    var data = google.visualization.arrayToDataTable(dt);
			    
			    var ac = new google.visualization.ComboChart(
			    		document.getElementById('chart_div'));
			    log(dt);
		        ac.draw(data, {
		          title : 'EIS Scenario Combo Chart',
		          width: 650,
		          height: 450,
		          vAxis: {title: "Columns / Measures"},
		          hAxis: {title: "Rows"},
		          seriesType: type,
		        });
			}
			
			if(type == 'pie'){
				for (var row in data) {
			    	var cnt = eis.scenario.rows.length-measure_count;		        
			    	temp_dt=[];
			    	
		        	var condition = true;
		        	var string = "";
		        	for (var item2 in data[row]) {
		        		
		        		if(cnt < 1 ){
		        			if(condition){
		        				string += ' | '+item2;			        				
		        				condition=false;
		        			}
		        			
		        			temp_dt.push(string);
		        			
			        		if(data[row][item2] == undefined){
			        			temp_dt.push(0);
			        		}else{
				        		temp_dt.push(parseInt(data[row][item2]));
			        		}
			        		dt.push(temp_dt);
			        		temp_dt=[];
			        	}
			        	else{
				        	string += data[row][item2]+" | ";
			        	}
			        	
			            cnt--;
			        }			        	
			    }
				
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Description');
				data.addColumn('number', 'Value');
				data.addRows(dt);
				new google.visualization.PieChart(
						document.getElementById('chart_div')).
						draw(data, 
						{width: 650, height: 450, is3D: true, 
							title:"EIS Scenario Pie Chart"});
				
			}
		    

	},
	
	removeTBItem : function(elm,id,type){
		var list = eis.scenario[type];
		eis.scenario[type] = jQuery.grep(list,function(elm){
			return elm != id;
		});
		jQuery(elm).parent('span').remove();
	},
	
	showFilterOption : function(field,name){
		var filterPanel = jQuery('#eis_filters');
		filterPanel.html('');
		var filterCfg = eis.rules.filters[field];
		
		if(filterCfg==undefined){ 
			jQuery('#eis_nofilter').tmpl({name:name})
			.appendTo(filterPanel);
			return;
		}
		var availOps = {eq:"Equals",gt:"Greater",lt:"Less",
				ge:"Greater/Equal",le:"Less/Equal"};
		var ops = jQuery([]);
		var values = jQuery([]);
		
		// ops
		for(var idx in filterCfg.op){
			ops.push(jQuery('#eis_option_tmpl')
					.tmpl({text:availOps[idx],value:idx}));
		}

		
		// values
		if(filterCfg.hasOwnProperty('values')){
			
			for(var idx in filterCfg.values){
				values.push(jQuery('#eis_option_tmpl')
						.tmpl({text:filterCfg.values[idx],value:idx}));
			}
			
		}else if(filterCfg.hasOwnProperty('table')){
			
		}else{
			jQuery('#eis_nofilter').tmpl({name:name})
			.appendTo(filterPanel);
			return;
		}
		
		jQuery('#eis_filtermain').tmpl({title:name,field:field})
												.appendTo(filterPanel);
		
		var div = jQuery('<div></div>');
		
		var valselect = jQuery('#eis_multiselect').tmpl({});
		valselect.appendTo(div);
		div.insertAfter(filterPanel.find('div:first'));
		
		div = jQuery('<div></div>');
		
		var opselect = jQuery('#eis_select').tmpl({});
			opselect.appendTo(div);
			div.insertAfter(filterPanel.find('div:first'));
			
		ops.appendTo(opselect);
		values.appendTo(valselect);
			
	},
	
	cancelFilter : function(){
		var filterPanel = jQuery('#eis_filters');
		filterPanel.html('');
	},
	
	addFilter : function(field){
		var filterPanel = jQuery('#eis_filters');
		var op = filterPanel.find('select:eq(0) :selected').val();
		var values = filterPanel.find('select:eq(1) :selected')
			.map(function(i,obj){return jQuery(obj).val();});
		var filter = field+'.'+op;
		
		eis.scenario.filters[filter] = values.get();
		eis.paintScenario();
		eis.cancelFilter();
		
		log(eis.scenario.filters);
	},
	
	removeFilter : function(elm,id){
		jQuery(elm).parent('span').remove();
		var list = eis.scenario.filters;
		delete eis.scenario.filters[id];
	}
	
};


// main app cycle


eis.init();


