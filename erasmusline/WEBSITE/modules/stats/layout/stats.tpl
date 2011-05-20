<div class="mainDiv">
	<div class="leftcol">


		<div id="eis_cube_container">
			<div>
				Cubes
			</div>
			<select size="1" name="Cubes" title="OLAP Cubes / KPI"
				onchange="eis.fillDimensionsAndMeasures();">
				<option selected="selected" value="">Selecione</option>
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
				Highlight
			</div>
			<div id="eis_highlight">
			</div>
		</div>
		
		
	</div>
	<div class="rightcol">
		<div>
			<label>label menu</label>
		</div>

		<div>
			<label>label colunas</label>
		</div>

		<div>
			<label>label linhas</label>
		</div>

		<div>
			<label>label filtros</label>
		</div>
		
		
		//Aqui estava a testar mas mais vale ignorar e fazer do 0
		
		<div class="workspace_fields">
			<div class="fields_list" title="COLUMNS">
				<div class="fields_list_header i18n">Columns</div>
				<div class="fields_list_body columns">
					<ul class="connectable ui-sortable"></ul>
				</div>
				<div class="clear"></div>
			</div>
			<div class="fields_list" title="ROWS">
				<div class="fields_list_header i18n">Rows</div>
				<div class="fields_list_body rows">
					<ul class="connectable ui-sortable">
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<div class="fields_list" title="FILTER">
				<div class="fields_list_header i18n">Filter</div>
				<div class="fields_list_body filter">
					<ul class="connectable ui-sortable">
					</ul>
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript" src="core/js/jquery/jquery-1.5.js"></script>
<script type="text/javascript" src="core/js/eis/jquery.blockUI.js"></script>
<script type="text/javascript" src="core/js/eis/eis.app.js"></script>
<ol id="qunit-tests"></ol>

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
