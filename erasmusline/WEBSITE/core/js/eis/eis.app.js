/**
 * EIS Application class
 * 
 */
var eis = {
		
	rules : {},
	
	init : function(){
		
		this.loadDependencies();
		
	},
	
	loadDependencies : function(){
		
		$.ajaxSetup({async: false});
		
		jQuery.getScript('core/js/eis/jquery.json-2.2.min.js');
		jQuery.getScript('core/js/eis/jsonpath-0.8.0.min.js');
		jQuery.getScript('core/js/eis/jquery.tmpl.min.js');
		
		//jQuery.getScript('core/js/eis/accessibilityInspector.js');
		
		// comment when done
		this.loadQunit();
		
		$.ajaxSetup({async: true});
	},
	loadBlockUI : function(){
		
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

		this.rpcCall("getRules",{},
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
					).appendTo('#eis_cube_select');
			
		}
		
	}
	
};

$.blockUI();

eis.init();
jQuery(document).ready(function($) {
	
	eis.loadRules();
	eis.fillCubes();
	$.unblockUI();
});

