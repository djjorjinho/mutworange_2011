<div class="mainDiv">
	<div class="leftcol">


		<div class="presentation_div_cubes border_eis" id="eis_cube_container">
			<div>
				Key Performance Indicators
			</div>
			<select name="Cubes" title="OLAP Cubes / KPI"
				onchange="eis.fillDimensionsAndMeasures();">
				<option selected="selected" value="">Custom</option>
			</select>
		</div>

		<div class="presentation_div_dimensions border_eis" id="eis_dimensions_container">
			<div>
				Dimensions
			</div>
			<div id="eis_dimensions">
				<ul>
					<li></li>
				</ul>
			</div>			
		</div>
		
		<div class="presentation_div_measures border_eis" id="eis_measures_container">
			<div>
				Measures
			</div>
			<div id="eis_measures">
				<ul>
					<li></li>
				</ul>
			</div>
		</div>
		
		<div class="presentation_div_filter border_eis" id="eis_filters_container">
			<div>
				Filters
			</div>
			<div id="eis_filters">
			</div>
		</div>
		
		<div class="presentation_div_HV border_eis" id="eis_highlight_container">
			<div>
				Highlight Values
			</div>
			<div id="eis_highlight">
				<select name="Highlight" title="Highlight Values">
					<option selected="selected" value="ge">Greater/Equal</option>
					<option value="le">Less/Equal</option>
					<option value="lt">Less</option>
					<option value="gt">Greater</option>
					<option value="eq">Equal</option>
				</select>
				<input type="text" title="Highlight Value input"/>
				<div id="colorSelector"><div style="background-color: #0000ff"></div></div> 
				<button title="Add Highlight Color" onclick="eis.addHlight(this);">Add</button>
				<button title="Reset Highlight Color" onclick="eis.resetHlight(this);">Reset</button>
			</div>
		</div>
		
		
	</div>
	<div class="rightcol">
		
		
			<div class="presentation_div_menu border_eis" title="Task Toolbar" id="eis_toolbar">
				<span>Tasks</span>
				<select name="Scenarios" title="Scenario Select" class="w100">
					<option selected="selected" value="">Select</option>
				</select>
				
				<button title="Save scenario configuration" onclick="eis.saveScenario();">Save</button>
				<button title="New Scenario" onclick="eis.newScenario();">New</button>
				<button title="Run Scenario" onclick="eis.runScenarioButton();">Run</button>
				<button title="Swap items between Column and Rows" onclick="eis.swapColumnsRows();">Swap</button>
				<button title="Export Scenario result to CSV" onclick="eis.exportScenario();">Export</button>
				<button title="Display Scenario Graph if available" onclick="eis.showGraph();">Graph</button>
				
				<select id="chart_select" name="Charts" title="Chart Select">
					<option selected="selected" value="bars">Bars</option>
					<option value="line">Lines</option>
					<option value="pie">Pie Plate</option>
				</select>
				
			</div>
			<div class="presentation_div_options border_eis" title="Column List">
				<div class="fields_list_header">
					Columns <button title="Add selected item to Columns" onclick="eis.addToColumns();">+</button>
				</div>
				<div id="columns_list" class="fields_list_body">
				</div>
				<div class="clear"></div>
			</div>
			<div class="presentation_div_options border_eis" title="Row List">
				<div class="fields_list_header">
				Rows 
					<button title="Add selected item to Rows" onclick="eis.addToRows();">+</button>
				</div>
				<div id="rows_list" class="fields_list_body">

				</div>
				<div class="clear"></div>
			</div>
			<div class="presentation_div_options border_eis" title="Filter List">
				<div class="fields_list_header">Filter</div>
				<div id="filters_list" class="fields_list_body">
				</div>
				<div class="clear"></div>
			</div>
			
		
		<div class="presentation_div border_eis">
			<div id="resultTableDiv"></div><div id="chart_div"></div>
		</div>


	</div>
</div>

<script type="text/javascript" src="core/js/jquery/jquery-1.5.js"></script>
<script type="text/javascript" src="core/js/eis/jquery.blockUI.js"></script>
<script type="text/javascript" src="core/js/eis/eis.app.js"></script>
<script type="text/javascript"> 
var _userid = '{$userid}';
var _userlevel = '{$userlevel}';
</script>
<ol id="qunit-tests"><li></li></ol>

<script id="eis_option_tmpl" type="text/x-jquery-tmpl">
    <option ${sel} value="${value}">${text}</option>
</script>

<script id="eis_mainitem_tmpl" type="text/x-jquery-tmpl">
    <li>
		<a title="Show/Hide Dimensions from category '${text}'" class="eis_dim_main" 
			onclick="eis.toggleShow(this);return false;"><b>${text}</b></a>
		<ul class="eis_dim_list" style="display:none"></ul>
	</li>
</script>

<script id="eis_listitem_tmpl" type="text/x-jquery-tmpl">
    <li>
		<a title="Select '${text}' from list" class="eis_dim_item" 
			onclick="eis.selectListItem('${item}',this);return false;">
		${text}
		</a>
	</li>
</script>

<script id="eis_tbdim_tmpl" type="text/x-jquery-tmpl">
    <span>
	<button title="Remove Dimension '${text}'" onclick="eis.removeTBItem(this,'${dim}','${type}')">${text}</button>
	<button title="Create Filter for Dimension '${text}'" onclick="eis.showFilterOption('${dim}','${text}')">F</button>
	</span>
	&nbsp;&nbsp;
</script>

<script id="eis_tbmes_tmpl" type="text/x-jquery-tmpl">
    <span>
	<button title="Remove measure '${text}'" onclick="eis.removeTBItem(this,'${mes}','${type}')">${text}</button>
	</span>
&nbsp;&nbsp;
</script>


<script id="eis_nofilter" type="text/x-jquery-tmpl">
    No filter available for <b>${name}</b>
</script>

<script id="eis_multiselect" type="text/x-jquery-tmpl">
    <select multiple="multiple" size="4">
	</select>
</script>

<script id="eis_select" type="text/x-jquery-tmpl">
    <select>
	</select>
</script>

<script id="eis_filtermain" type="text/x-jquery-tmpl">
    <div> <b>${title}</b> </div>

	<button title="Add Filter" onclick="eis.addFilter('${field}')">Add</button>
	<button title="Cancel Filter Adding" onclick="eis.cancelFilter()">Cancel</button>
</script>

<script id="eis_tbfil_tmpl" type="text/x-jquery-tmpl">
 	<span>
	<button title="Remove filter '${text}'" onclick="eis.removeFilter(this,'${field}')">${text}</button>
	</span>
&nbsp;&nbsp;
</script>
