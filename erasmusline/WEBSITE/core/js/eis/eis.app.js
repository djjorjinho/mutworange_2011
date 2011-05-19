/**
 * EIS Application class
 * 
 */
function EIS(){
}
EIS.prototype = {
	init : function(){
		this.loadDependencies();
	},
	loadDependencies : function(){
		jQuery.getScript('core/js/eis/jquery.json-2.2.min.js');
		jQuery.getScript('core/js/eis/jquery.tmpl.min.js');
		jQuery.getScript('core/js/eis/jsonpath-0.8.0.min.js');
		//jQuery.getScript('core/js/eis/accessibilityInspector.js');
		
		// comment when done
		this.loadQunit();
		//this.runTests();
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
	rpcCall : function(func,args,callSuccess,callError){
		jQuery.ajax({
			url: "modules/stats/rpc.php",
			type : "POST",
			data : {method : func, 
					params : jQuery.toJSON(args)},
			dataType : 'json',
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
	}
};

var eis = new EIS();
eis.init();