<div class="mainDiv">
	<div class="leftcol">


		<div id="eis_cube_container">
			<select size="1" name="Cubes" id="eis_cube_select">
				<option selected value="Selecione">Selecione</option>
			</select>
		</div>

		<dir>
			<table name="Dimensions">
				<tr>Tabela de dimensoes
				</tr>
			</table>
		</dir>

		<dir>
			<table name="Measures">
				<tr>Tabela de medidas
				</tr>
			</table>
		</dir>
		<h3 class="i18n">Dimensions</h3>
		<div class="sidebar_inner dimension_tree">
			<ul style="display: block;">
				<li><span class="root expand"> 
				
				//Local de interaçao para a ul, mesmo sera feito para medida etc
				
				{iteration:iDimension}
                     {$dim1}
               {/iteration:iDimension}
				
				//Tava a ponderar usar uls como o gajo fez, basta mudar o 
				//'display' ao click pra aprecer ou n
				<a href="#" rel="d1"
						class="folder_expand">Time</a> </span>
					<ul style="display: block;">
						<li class="hierarchy ui-draggable"><a href="#">Time</a>
						</li>
						<li title="1" class="ui-draggable"><a href="#" class="dimension"
							rel="d1_0_0" title="[Time].[Year]">Year</a></li>
						<li title="2" class="ui-draggable"><a href="#" class="dimension"
							rel="d1_0_1" title="[Time].[Quarter]">Quarter</a></li>
						<li title="3" class="ui-draggable"><a href="#" class="dimension"
							rel="d1_0_2" title="[Time].[Month]">Month</a></li>
						<li class="hierarchy ui-draggable"><a href="#">Weekly</a></li>
						<li title="4" class="ui-draggable"><a href="#" class="dimension"
							rel="d1_1_0" title="[Time.Weekly].[(All)]"> All Weekly</a></li>
						<li title="5" class="ui-draggable"><a href="#" class="dimension"
							rel="d1_1_1" title="[Time.Weekly].[Year]">Year</a></li>
						<li title="6" class="ui-draggable"><a href="#" class="dimension"
							rel="d1_1_2" title="[Time.Weekly].[Week]">Week</a></li>
						<li title="7" class="ui-draggable"><a href="#" class="dimension"
							rel="d1_1_3" title="[Time.Weekly].[Day]">Day</a></li>
					</ul></li>
				<li><span class="root collapsed"><a href="#" rel="d2"
						class="folder_collapsed">Product</a> </span>
					<ul style="display: none;">
						<li title="8" class="ui-draggable"><a href="#" class="dimension"
							rel="d2_0_0" title="[Product].[(All)]"> All Product</a></li>
						<li title="9" class="ui-draggable"><a href="#" class="dimension"
							rel="d2_0_1" title="[Product].[Product Family]">Product Family</a>
						</li>
						<li title="10" class="ui-draggable"><a href="#" class="dimension"
							rel="d2_0_2" title="[Product].[Product Department]">Product
								Department</a></li>
						<li title="11" class="ui-draggable"><a href="#" class="dimension"
							rel="d2_0_3" title="[Product].[Product Category]">Product
								Category</a></li>
						<li title="12" class="ui-draggable"><a href="#" class="dimension"
							rel="d2_0_4" title="[Product].[Product Subcategory]">Product
								Subcategory</a></li>
						<li title="13" class="ui-draggable"><a href="#" class="dimension"
							rel="d2_0_5" title="[Product].[Brand Name]">Brand Name</a></li>
						<li title="14" class="ui-draggable"><a href="#" class="dimension"
							rel="d2_0_6" title="[Product].[Product Name]">Product Name</a></li>
					</ul></li>
				<li><span class="root collapsed"><a href="#" rel="d3"
						class="folder_collapsed">Gender</a> </span>
					<ul style="display: none;">
						<li title="15" class="ui-draggable"><a href="#" class="dimension"
							rel="d3_0_0" title="[Gender].[(All)]"> All Gender</a></li>
						<li title="16" class="ui-draggable"><a href="#" class="dimension"
							rel="d3_0_1" title="[Gender].[Gender]">Gender</a></li>
					</ul></li>
			</ul>
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
