<h2>Student Application Form</h2>

<form action=" " method="post" enctype="multipart/form-data" id="studApplicForm" name="studApplicForm" >

    <div class="TRdiv">
<label for="acaYear"><span>Academic year: </span></label>
<input class="validate[required] text-input" type="text" id="acaYear" name="acaYear" value="{$acaYear|htmlentities}" />
<span class="req" id="msgAcaYear">{$msgAcaYear|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="study"><span>Field of Study: </span></label>
            <select name="study" value="{$study}" >
               {iteration:iStudy}
                     {$stud}
               {/iteration:iStudy}
            </select>
            <span class="req" id="msgStudy">{$msgStudy|htmlentities}</span>
</div>  

<h3>Sending Institution</h3>
<fieldset>
<legend>Contact info</legend>
<div class="TRdiv">
<label for="sendInstName"><span>Name: </span></label>
<input type="text" id="sendInstName" name="sendInstName" value="{$sendInstName|htmlentities}" />
</div>
<div class="TRdiv">
    <label for="sendInstAddress"><span>Address: </span></label>
<input class="validate[required,custom[onlyLetterNumber]] text-input" type="text" id="sendInstAddress" name="sendInstAddress" value="{$sendInstAddress|htmlentities}" />
</div>

<div class="TRdiv">
<label for="sendDepCoorName"><span>Departmental co-ordinator – name: </span></label>
<input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="sendDepCoorName" onkeyup="lookup2(this.value);" onclick="fill2();" name="sendDepCoorName" value="{$sendDepCoorName|htmlentities}" />
<span class="req" id="msgSendDepCoorName">{$msgSendDepCoorName|htmlentities}</span>
</div>
<div class="suggestionsBox" id="suggestions2" style="display: none;">
		<div class="suggestionList" id="autoSuggestionsList2">
			&nbsp;
                </div>
	</div>  

<div class="TRdiv">
<label for="sendDepCoorTel"><span>Departmental co-ordinator – telephone: </span></label>
<input class="validate[required,custom[onlyNumberSp]] text-input" type="text" id="sendDepCoorTel" name="sendDepCoorTel" value="{$sendDepCoorTel|htmlentities}" />
<span class="req" id="msgSendDepCoorTel">{$msgSendDepCoorTel|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="sendDepCoorMail"><span>Departmental co-ordinator – e-mail box: </span></label>
<input class="validate[required,custom[email]] text-input" type="text" id="sendDepCoorMail" name="sendDepCoorMail" value="{$sendDepCoorMail|htmlentities}" />
<span class="req" id="msgSendDepCoorMail">{$msgSendDepCoorMail|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="sendInstCoorName"><span>Institutional co-ordinator – name: </span></label>
<input type="text" id="sendInstCoorName" name="sendInstCoorName" value="{$sendInstCoorName|htmlentities}" />
</div>

<div class="TRdiv">
<label for="sendInstCoorTel"><span>Institutional co-ordinator – telephone: </span></label>
<input type="text" id="sendInstCoorTel" name="sendInstCoorTel" value="{$sendInstCoorTel|htmlentities}" />
</div>

<div class="TRdiv">
<label for="sendInstCoorMail"><span>Institutional co-ordinator – e-mail box: </span></label>
<input type="text" id="sendInstCoorMail" name="sendInstCoorMail" value="{$sendInstCoorMail|htmlentities}" />
</div>

</fieldset>

<h3>Student's personal data</h3>
<p>(to be completed by the student applying)</p>
<fieldset>
<div class="TRdiv">
<label for="fName"><span>First name: </span></label>
<input class="validate[required] text-input" type="text" id="fName" name="fName" value="{$fName|htmlentities}" />
</div>

<div class="TRdiv">
<label for="faName"><span>Family name: </span></label>
<input class="validate[required] text-input" type="text" id="faName" name="faName" value="{$faName|htmlentities}" />
</div>

<div class="TRdiv">
<label for="dateBirth"><span>Date of birth: </span></label>
<input class="validate[required] text-input" type="text" id="dateBirth" name="dateBirth" value="{$dateBirth|htmlentities}" />
</div>

<div class="TRdiv">
<span>Sex: </span>
<input class="validate[required] text-input" type="text" id="sex" name="sex" value="{$sex|htmlentities}" />
</div>

<div class="TRdiv">
<label for="nation"><span>Nationality; </span></label>
<input class="validate[required] text-input" type="text" id="nation" name="nation" value="{$nation|htmlentities}" />
</div>

<div class="TRdiv">
<label for="birthPlace"><span>Place of Birth: </span></label>
<input class="validate[required] text-input" type="text" id="birthPlace" name="birthPlace" value="{$birthPlace|htmlentities}" />
</div>

<div class="TRdiv">
<label for="cAddress"><span>Current address: </span></label>
<input class="validate[required] text-input" type="text" id="cAddress" name="cAddress" value="{$cAddress|htmlentities}" />
<span class="req" id="msgCAddress">{$msgCAddress|htmlentities}</span>
</div>
<div class="TRdiv">
<label for="daateValid"><span>Current address is valid until: </span></label>
<input class="validate[required,custom[date]] text-input" type="text" id="daateValid" name="daateValid" value="{$daateValid|htmlentities}" />
<span class="req" id="msgDaateValid">{$msgDaateValid|htmlentities}</span>
</div>
<div class="TRdiv">
<label for="cTel"><span>Tel: </span</label>
<input class="validate[required] text-input" type="text" id="cTel" name="cTel" value="{$cTel|htmlentities}" />
<span class="req" id="msgCTel">{$msgCTel|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="pAddress"><span>Permanent address (if different): </span></label>
<input class="validate[required] text-input" type="text" id="pAddress" name="pAddress" value="{$pAddress|htmlentities}" />
</div>
<div class="TRdiv">
<label for="pTel"><span>Tel: </span></label>
<input class="validate[required] text-input" type="text" id="pTel" name="pTel" value="{$pTel|htmlentities}" />
</div>
<div class="TRdiv">
<label for="mail"><span>Email: </span></label>
<input class="validate[required] text-input" type="text" id="mail" name="mail" value="{$mail|htmlentities}" />
</div>
</fieldset>

<h3>Institution which will receive this application form.</h3>

<fieldset>
<div class="TRdiv">
<label for="recInstitut"><span>Receiving Institution: </span></label>
<input class="validate[required] text-input" type="text" id="recInstitut" onkeyup="lookup3(this.value);" onclick="fill3();" name="recInstitut" value="{$recInstitut|htmlentities}" />
<span class="req" id="msgRecInstitut">{$msgRecInstitut|htmlentities}</span>
</div>
    
    <div class="suggestionsBox3" id="suggestions3" style="display: none;">
		<div class="suggestionList3" id="autoSuggestionsList3">
			&nbsp;
                </div>
	</div>  

<div class="TRdiv">
<label for="coountry"><span>Country: </span></label>
<input class="validate[required] text-input" type="text" id="coountry" name="coountry" value="{$coountry|htmlentities}" />
<span class="req" id="msgCoountry">{$msgCoountry|htmlentities}</span>
</div>

<div class="TRdiv">
<span>Period of study: </span>
<label for="from">From: </label>
<input class="validate[required,custom[date]] text-input" type="text" id="daateFrom" name="daateFrom" value="{$daateFrom|htmlentities}" />
<span class="req" id="msgDaateFrom">{$msgDaateFrom|htmlentities}</span>
<label for="from">Untill: </label>
<input class="validate[required,custom[date]] text-input" type="text" id="daateUntill" name="daateUntill" value="{$daateUntill|htmlentities}" />
<span class="req" id="msgDaateUntill">{$msgDaateUntill|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="duration"><span>Duration of stay (in months): </span></label>
<input class="validate[required,custom[onlyNumberSp]] text-input" type="text" id="duration" name="duration" value="{$duration|htmlentities}" />
<span class="req" id="msgDuration">{$msgDuration|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="ectsPoints"><span>Number of expected ECTS credits: </span></label>
<input class="validate[required,custom[onlyNumberSp]] text-input" type="text" id="ectsPoints" name="ectsPoints" value="{$ectsPoints|htmlentities}" />
<span class="req" id="msgEctsPoints">{$msgEctsPoints|htmlentities}</span>
</div>
</fieldset>

<fieldset>
<legend>Motivation</legend>
<div class="TRdiv">
<label for="motivation"><span>Briefly state the reasons why you wish to study abroad</span></label>
<input class="validate[required,custom[onlyLetterNumber]] text-input" type="text" id="motivation" name="motivation" value="{$motivation|htmlentities}" />
<span class="req" id="msgMotivation">{$msgMotivation|htmlentities}</span>
</div>
</fieldset>

<h3>Language competence</h3>

<fieldset>
<div class="TRdiv">
<label for="motherTongue"><span>Mother tongue: </span></label>
<input class="validate[required] text-input" type="text" id="motherTongue" name="motherTongue" value="{$motherTongue|htmlentities}" />
<span class="req" id="msgMotherTongue">{$msgMotherTongue|htmlentities}</span>
</div>
<div class="TRdiv">
<label for="instrLanguage"><span>Language of instruction at home institution (if different): </span></label>
<input class="validate[required] text-input" type="text" id="instrLanguage" name="instrLanguage" value="{$instrLanguage|htmlentities}" />
<span class="req" id="msgInstrLanguage">{$msgInstrLanguage|htmlentities}</span>
</div>

</fieldset>

<fieldset>
<table id="languageTable">
<tr>
<th>Other languages</th>
<th>I am currently studying this language</th>
<th> I have sufficient knowledge to follow lectures</th>
<th>I would have sufficient knowledge to follow lectures if I had some extra preparation</th>
</tr>
<tr>
    <th></th>
    <th>Yes - No</th>
    <th>Yes - No </th>
    <th>Yes - No</th>
    <th></th>
</tr>
<tr>
<td><input type="button" name="addLanguage" value="Add" id="addLanguage"/><input type="button" name="removeLanguage" value="Remove" id="remLanguage" /><input type="hidden" id="languageCount" name="languageCount" value="{$languageCount}" /></td>
</tr>

{iteration:iLanguages}
{$language}
{/iteration:iLanguages}

</table>
</fieldset>

<h3>Work experience related to current study (if relevant)</h3>

<fieldset>
<table id="workTable">
<tr>
<th>Type of work experience</th>
<th>Firm/organisation</th>
<th>Dates</th>
<th>Country</th>
<th></th>
</tr>
<tr>
                            <td></td><td></td><td></td><td></td>
                            <td><input type="button" name="addWork" value="Add" id="addWork"/><input type="button" name="removeWork" value="Remove" id="remWork" /><input type="hidden" id="workCount" name="workCount" value="{$workCount}" /></td>
                        </tr>

{iteration:iWorks}
{$work}
{/iteration:iWorks}

</table>
</fieldset>

<h3>Previous and current study</h3>

<fieldset>
<div class="TRdiv">
<label for="diplome"><span>Diploma/degree for which you are currently studying: </span></label>
<input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="diplome" name="diplome" value="{$diplome|htmlentities}" />
<span class="req" id="msgDiplome">{$msgDiplome|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="yEducation"><span>Number of higher education study years prior to departure abroad: </span></label>
<input class="validate[required,custom[onlyLetterNumber]] text-input" type="text" id="yEducation" name="yEducation" value="{$yEducation|htmlentities}" />
<span class="req" id="msgYEducation">{$msgYEducation|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="abroad"><span>Have you already been studying abroad?: </span></label>
            <span>Yes</span><input class="validate[required] radio" type="radio" {$abroadYes} name="abroad" value="Yes" id="eena"  />
            <span>No</span><input class="validate[required] radio" type="radio" {$abroadNo} name="abroad" value="No" id="nula"  />
            <span class="req" id="msgSex">{$msgAbroad|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="whichInst"><span>At which Institution</span></label>
<input class="validate[required] text-input" type="text" id="whichInst" name="whichInst" value="{$whichInst|htmlentities}" />
<span class="req" id="msgWhichInst">{$msgWhichInst|htmlentities}</span>
</div>

<p><strong>The attached Transcript of records includes full details of previous and current higher education study. </strong></p>

</fieldset>

<p><strong>PLEASE ATTACH YOUR TRANSCIPT OF RECORDS WITH PREVIOUS COURSE UNITS TAKEN !</strong></p>

<h3>Receiving institution</h3>

<fieldset>
<p>We hereby acknowledge receipt of the application, the proposed learning agreement and the candidate’s Transcript of records.
</p>

<div class="TRdiv">
<label for="accepted">The above mentioned student is: </label>
            <span>Provisionally accepted at our institution</span><input class="validate[required] radio" type="radio" name="accepted" value="1" id="1" {$acceptedYes} />
            <span>Not accepted at our institution</span><input type="radio" class="validate[required] radio" name="accepted" value="0" id="0" {$acceptedNo} />
            <span class="req" id="msgAccepted">{$msgAccepted|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="signDepSign"><span>Departamental coordinator's signature</span></label>
<input type="text" id="signDepSign" name="signDepSign" value="{$signDepSign|htmlentities}" />
<span class="req" id="msgSignDepSign">{$msgSignDepSign|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="signDepSignDate"><span>Date: </span></label>
<input class="validate[required,custom[date]] text-input" type="text" id="signDepSignDate" name="signDepSignDate" value="{$signDepSignDate|htmlentities}" />
<span class="req" id="msgSignDepSignDate">{$msgSignDepSignDate|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="signInstSign"><span>Institutional coordinator's signature</span></label>
<input type="text" id="signInstSign" name="signInstSign" value="{$signInstSign|htmlentities}" />
<span class="req" id="msgSignInstSign">{$msgSignInstSign|htmlentities}</span>
</div>

<div class="TRdiv">
<label for="signInstSignDate"><span>Date: </span></label>
<input class="validate[required,custom[date]] text-input" type="text" id="signInstSignDate" name="signInstSignDate" value="{$signInstSignDate|htmlentities}" />
<span class="req" id="msgSignInstSignDate">{$msgSignInstSignDate|htmlentities}</span>
</div>

</fieldset>

<fieldset>
<legend>Submit the application</legend>
        <div class="TRdiv">
		<input type="hidden" name="formAction" id="formRegister" value="doApplic" />
		<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
	</div>
</fieldset>
</form>

