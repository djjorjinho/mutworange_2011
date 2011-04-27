<form action=" " method="post" enctype="multipart/form-data" id="confirmArrival">	
<fieldset>
    <legend>Confirmation of Arrival</legend>  
        <p>
            <span> Academic Year </span>
            {$AcademicYear}
        </p>
	<div>
            Hereby, I confirm the arrival of the student: 
		<input type="text" size="30" value="" id="inputString" onkeyup="lookup(this.value);" onblur="fill();" />
	</div>
	<div class="suggestionsBox" id="suggestions" style="display: none;">
		<div class="suggestionList" id="autoSuggestionsList">
			&nbsp;
                </div>
	</div>
         <p>
            <span>Host Institution :</span>
            <input type="text" size="40" value="" id="labelInstitution" />
	</p>
        <p>Date of arrival: <input type="text" id="datepicker" value=""></p>
		
</fieldset>
<p>
		<input type="hidden" name="formAction" id="confirmArrival" value="doConfirm" />
		<input class="button" name="btnConfirm" id="btnConfirm" type="submit" value="Confirm"/>
</p>    
</form>

