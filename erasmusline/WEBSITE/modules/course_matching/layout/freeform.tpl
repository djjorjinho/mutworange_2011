<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
    <h2>Course Matching</h2>
	<form action="index.php?module=course_matching&view=matchsuccess" method="post" enctype="multipart/form-data">
		<div class="TRdiv">
			<label for="Name"><span>Course Name</span></label>
			<input type="text" name="Name" id="txtCourseName" />
		</div>
		<div class="TRdiv">
			<label for="Ects"><span>ECTS</span></label>
			<input type="text" name="Ects" id="txtCourseEcts" />
		</div>
		<div class="TRdiv">
			<label for="Description"><span>Description</span></label>
			<textarea style="width:250px;" rows="10" name="Description" id="txtCourseDesc"></textarea>
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