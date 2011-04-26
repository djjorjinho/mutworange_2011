<div class="mainDiv">
<form action="" method="post" enctype="multipart/form-data" id="cert">	
<fieldset>
    <legend>Certificate of Stay</legend>       
     <div class="TRdiv">
           <label for="academicYear"><span>Academic Year : </span></label>
            <input type="text" value="{$academicYear}" id="academicYear" name="academicYear" disabled="true" />
        </div>
	<div class="TRdiv">
           <label for="student"><span>Student : </span></label>
		<input type="text" size="30" name="student" value="{$student}" id="inputString" onkeyup="lookup(this.value);" onclick="fill();" class="validate[required] text-input" />
                 <span class="req" id="msgStudent">{$msgStudent|htmlentities}</span>	
	</div>
        
	<div class="suggestionsBox" id="suggestions" style="display: none;">
		<div class="suggestionList" id="autoSuggestionsList">
			&nbsp;
                </div>
	</div>
        <p>coming from:</p>
         <div class="TRdiv">
            <label for="hostInstitution"><span>Host Institution : </span></label>
            <input type="text" class="validate[required] text-input" value="{$hostInstitution}" id="hostInstitution" name="hostInstitution" />
             <span class="req" id="msgHostInstitution">{$msgHostInstitution|htmlentities}</span>	
	</div>
        <p>has been following studies in this University, during the period:</p>
        <div class="TRdiv">
            <label for="dateArrival"><span>Date Of Arrival :  </span></label>
            <input type="text" class="validate[required] text-input" id="dateArrival" name="dateArrival" value="{$dateArrival}" />
        <span class="req" id="msgDateArrival">{$msgDateArrival|htmlentities}</span>	</div>
        <div class="TRdiv">
            <label for="dateDeparture"><span>Date Of Departure : </span></label>
            <input type="text" class="validate[required] text-input" id="dateDeparture" name="dateDeparture" value="{$dateDeparture}" />
        <span class="req" id="msgDateDeparture">{$msgDateDeparture|htmlentities}</span>	</div>
</fieldset>
        <div class="TRdiv">              
		<input type="hidden" name="formAction" id="formCert" value="doSubmit" />
		<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
	</div>    
</form>