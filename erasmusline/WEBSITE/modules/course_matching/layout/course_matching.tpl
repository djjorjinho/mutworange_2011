<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
    <h2>Course Matching</h2>
<form action="index.php?module=course_matching&view=matchsuccess" method="post" enctype="multipart/form-data">
	<div class="TRdiv">
		<label for="course"><span>Original Course</span></label>
		<select name="course" class="field">
			{iteration:iCourses}
				{$courses}
			{/iteration:iCourses}
		</select>
	</div>
	<div class="TRdiv">
		<label for="Institution"><span>Target Institution</span></label>
		<select name="institution" class="field">
			{iteration:iInstitutions}
				{$institution}
			{/iteration:iInstitutions}
		</select>
	</div>
	<input class="button" name="btnMatch" id="btnMatch" type="submit" value="Match"/>
</form>
</div>