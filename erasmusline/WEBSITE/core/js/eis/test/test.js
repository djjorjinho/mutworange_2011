console.log("testing");
module("EIS");
asyncTest( "json rpc call", function() {
	  expect( 3 );
	 
	  eis.rpcCall("getRules",{},
				function(result){
					console.log(result);
					ok(true,"getrules");
	  			}, 
				function(error){
					console.log(error);
					ok(false,"getrules_error");

				});
	  
	  eis.rpcCall("hello",{name:"world"},
				function(result){
				}, 
				function(error){
					equals("NO_METHOD",error['message']);

				}); 
	 
	  eis.rpcCall("ping",{name:"world"},
				function(result){
					equals("world",result.ping);

				}, 
				function(error){
					ok(false,"failed");
				});
	 
	  setTimeout( start, 2000 );
});

test("selected items",function(){
	
	jQuery('#eis_cube_container select').val('fact_efficiency');
	
	eis.fillDimensionsAndMeasures();
	
	eis.selectListItem('measure.M1');
	eis.addToColumns();
	
	equals('measure.M1',eis.scenario.columns[0]);
	
});