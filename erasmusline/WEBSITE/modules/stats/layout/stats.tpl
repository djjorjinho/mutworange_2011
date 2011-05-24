<div class="mainDiv">
	<div class="leftcol">


		<div id="eis_cube_container">
			<div>
				Key Performance Indicators
			</div>
			<select name="Cubes" title="OLAP Cubes / KPI"
				onchange="eis.fillDimensionsAndMeasures();">
				<option selected="selected" value="">Select</option>
			</select>
		</div>

		<div id="eis_dimensions_container">
			<div>
				Dimensions
			</div>
			<div id="eis_dimensions">
				<ul>
					<li></li>
				</ul>
			</div>			
		</div>
		
		<div id="eis_measures_container">
			<div>
				Measures
			</div>
			<div id="eis_measures">
				<ul>
					<li></li>
				</ul>
			</div>
		</div>
		
		<div id="eis_filters_container">
			<div>
				Filters
			</div>
			<div id="eis_filters">
			</div>
		</div>
		
		<div id="eis_highlight_container">
			<div>
				Highlight Values
			</div>
			<div id="eis_highlight">
				<select name="Highlight" title="Highlight Values">
					<option selected="selected" value="ge">Greater/Equal</option>
					<option value="le">Less/Equal</option>
					<option value="lt">Less</option>
					<option value="gt">Greater</option>
					<option value="qe">Equal</option>
				</select>
				<input type="text" title="Highlight Value input"/>
				<div id="colorSelector"><div style="background-color: #0000ff"></div></div> 
				<button onclick="eis.addFilter(this);">Add</button>
			</div>
		</div>
		
		
	</div>
	<div class="rightcol">
		
		<div class="workspace_fields">
			<div class="fields_list" title="Toolbar" id="eis_toolbar">
				<div class="fields_list_header i18n">
				Scenario
				</div>
				<select name="Scenarios" title="Scenario Select">
					<option selected="selected" value="">Select</option>
				</select>
				
				<button onclick="eis.saveScenario();">Save</button>
				<button onclick="eis.newScenario();">New</button>
				<button onclick="eis.runScenario();">Run</button>
				<button onclick="eis.swapColumnsRows();">Swap</button>
				<button onclick="eis.exportScenario();">Export</button>
				
			</div>
			<div class="fields_list" title="COLUMNS">
				<div class="fields_list_header i18n">
				Columns 
					<button onclick="eis.addToColumns();">+</button>
				</div>
				<div class="fields_list_body columns">
					<ul class=""></ul>
				</div>
				<div class="clear"></div>
			</div>
			<div class="fields_list" title="ROWS">
				<div class="fields_list_header i18n">
				Rows 
					<button onclick="eis.addToRows();">+</button>
				</div>
				<div class="fields_list_body rows">
					<ul class="">
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<div class="fields_list" title="FILTER">
				<div class="fields_list_header i18n">Filter</div>
				<div class="">
					<ul class="">
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			
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
			onclick="eis.selectListItem('${item}');return false;">
		${text}
		</a>
	</li>
</script>
