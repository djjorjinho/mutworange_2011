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
				<button onclick="eis.addHlight(this);">Add</button>
				<button onclick="eis.resetHlight(this);">Reset</button>
			</div>
		</div>
		
		
	</div>
	<div class="rightcol">
		
		
			<div class="presentation_div_menu border_eis" title="Toolbar" id="eis_toolbar">
				<span>Tasks</span>
				<select name="Scenarios" title="Scenario Select">
					<option selected="selected" value="">Select</option>
				</select>
				
				<button onclick="eis.saveScenario();">Save</button>
				<button onclick="eis.newScenario();">New</button>
				<button onclick="eis.runScenarioButton();">Run</button>
				<button onclick="eis.swapColumnsRows();">Swap</button>
				<button onclick="eis.exportScenario();">Export</button>
				<button onclick="eis.showGraph();">Graph</button>
				
			</div>
			<div class="presentation_div_options border_eis" title="COLUMNS">
				<div class="fields_list_header i18n">
				Columns 
					<button onclick="eis.addToColumns();">+</button>
				</div>
				<div id="columns_list" class="fields_list_body columns">
					
				</div>
				<div class="clear"></div>
			</div>
			<div class="presentation_div_options border_eis" title="ROWS">
				<div class="fields_list_header i18n">
				Rows 
					<button onclick="eis.addToRows();">+</button>
				</div>
				<div id="rows_list" class="fields_list_body rows">

				</div>
				<div class="clear"></div>
			</div>
			<div class="presentation_div_options border_eis" title="FILTER">
				<div class="fields_list_header i18n">Filter</div>
				<div id="filters_list" class="fields_list_body filters">
				</div>
				<div class="clear"></div>
			</div>
			
		
		<div class="presentation_div border_eis" id="resultTableDiv">
		</div>

	</div>
</div>

<script type="text/javascript" src="core/js/jquery/jquery-1.5.js"></script>
<script type="text/javascript" src="core/js/eis/jquery.blockUI.js"></script>
<script type="text/javascript" src="core/js/eis/eis.app.js"></script>
<script type="text/javascript"> 
var _userid = {$userid};
var _userlevel = '{$userlevel}';
</script>
<ol id="qunit-tests"><li></li></ol>

<script id="eis_option_tmpl" type="text/x-jquery-tmpl"> 
    <option ${sel} value="${value}">${text}</option>
</script>

<script id="eis_mainitem_tmpl" type="text/x-jquery-tmpl"> 
    <li>
		<a class="eis_dim_main" 
			onclick="eis.toggleShow(this);return false;"><b>${text}</b></a>
		<ul class="eis_dim_list" style="display:none"></ul>
	</li>
</script>

<script id="eis_listitem_tmpl" type="text/x-jquery-tmpl"> 
    <li>
		<a class="eis_dim_item" 
			onclick="eis.selectListItem('${item}',this);return false;">
		${text}
		</a>
	</li>
</script>

<script id="eis_tbdim_tmpl" type="text/x-jquery-tmpl"> 
    <span><button onclick="eis.removeTBItem(this,'${dim}','${type}')">${text}</button>
	<button onclick="eis.showFilterOption('${dim}','${text}')">F</span>
	&nbsp;&nbsp;
</script>

<script id="eis_tbmes_tmpl" type="text/x-jquery-tmpl"> 
    <span>
	<button onclick="eis.removeTBItem(this,'${mes}','${type}')">${text}</button>
	</span>
&nbsp;&nbsp;
</script>


<script id="eis_nofilter" type="text/x-jquery-tmpl"> 
    No filter available for <strong>${name}</strong>
</script>

<script id="eis_multiselect" type="text/x-jquery-tmpl"> 
    <select multiple="multiple" size="5">
	</select>
</script>

<script id="eis_select" type="text/x-jquery-tmpl"> 
    <select>
	</select>
</script>

<script id="eis_filtermain" type="text/x-jquery-tmpl"> 
    <div> ${title} </div>

	<button onclick="eis.addFilter('${field}')">Add</button>
	<button onclick="eis.cancelFilter()">Cancel</button>
</script>

<script id="eis_tbfil_tmpl" type="text/x-jquery-tmpl">
 	<span>
	<button onclick="eis.removeFilter(this,'${field}')">${text}</button>
	</span>
&nbsp;&nbsp;
</script>