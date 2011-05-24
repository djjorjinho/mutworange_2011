<script type="text/javascript" src="./core/js/jquery/jquery-1.5.js"></script>
<script type="text/javascript">
	var m = '{$matches}';
	jQuery(document).ready(function() {
		jQuery(".courseContent").hide();
	});
	function nameClick(cid){
		jQuery("#courseContent" + cid).slideToggle();
	}
</script>
<style type="text/css">
	.lightCourseRow{
		background-color:#D0D0D0;
	}
	.darkCourseRow{
		background-color:#DDDDDD;
	}
</style>
<table style="width:100%">
{iteration:courseDescriptions}
		<tr class="{$cClass}" onclick="nameClick({$cid})" style="cursor:pointer;"><td border=1px 1px 0px 0px;"><div id="courseName"><b>{$cName}</b></div></td><td style="text-align:right;">{$cScore}</td></tr>
		<tr class="{$cClass}"><td colspan="2"><div id="courseContent{$cid}" class="courseContent">
		<div id="courseEcts"><b>ECTS:</b> {$cEcts}</div>
		<div id="courseDescription"><b>Description:</b> {$cDesc}</div>
		</div></td></tr>
{/iteration:courseDescriptions}
</table>