<div id="infoxAdmin">
<div id="infoxAdminTables" style="float: left; width: 300px;">
Select a table to transfer data from:<br />
<br />
<form method="post" action="index.php?module=infox&view=admin">
<select name="table" size="15">
{iteration:iTables}
{$tables}
{/iteration:iTables}
</select>
<input type="submit" value="SELECT" />
</form>
</div>


<div id="infoxAdminData" style="float: left; width: 380px; overflow: auto;">
{option:oTable}
Select data to transfer:<br />
<br />
<form method="post" action="index.php?module=infox">
<input type="hidden" name="table" value="{$table}" />
<div style="float: left; width: 1000px;">
{iteration:iData}
{$data}
{/iteration:iData}
</div>
<input type="submit" value="SELECT" name="admin" />
</form>
{/option:oTable}
</div>
<br />
<br />
<div style="float: left; width: 600px;">
{$debug}
</div>