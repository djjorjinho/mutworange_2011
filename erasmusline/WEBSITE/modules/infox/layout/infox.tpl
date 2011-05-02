<div id="infox">
<form method="post" action="index.php?module=infox&view=transfer" name="selectUniversity">
Please select your destination-university:
<select name="idUni">
<option value="0"></option>
<option value="0"> - - - - - </option>
{iteration:iUniversity}
{$university}
{/iteration:iUniversity}
</select>
<input type="hidden" name="json" value="{$json}" />
<input type="hidden" name="formAction" id="formTransfer" value="doTransfer" />
<input type="submit" value="SEND">
</form>
</div>
<br />
<br />
{$debug}