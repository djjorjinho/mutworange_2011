<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
<h2>Learning agreement</h2>

<form action="" method="post" enctype="multipart/form-data" id="lagreement" name="lagreement">
    <p><span class="req">{$error}</span></p>
    <p>
        <span>Academic Year: </span>
        {$acaYear}
    </p>

    <p>
        <span>Field of Study: </span>
        {$study}
    </p>


    <fieldset>
        <p>
            <span>Name of student: </span>
            {$nameStudent}
        </p>

        <p>
            <span>Sending institution: </span>
            {$sendingInstitution}
        </p>
        <p>
            <span>Country: </span>
            {$countrySendingInstitution}
        </p>
        <p>
            <span>ECTS credits:</span> {$credits}
        </p>
    </fieldset>


    <h3>Details of the proposed study programme abroad/learning agreement.</h3>

    <fieldset>
        <p>
            <span>Receiving Institution: </span>
            {$receivingInstitution}
        </p>

        <p>
            <span>Country: </span>
            {$countryReceivingInstitution}
        </p>

    </fieldset>

    <fieldset>
        <table id="coursesTable">
            <tr>
                <th>Course unit code (if any) and page no. of the information package</th>
                <th>Course unit title (as indicated in the information package)</th>
                <th>Number of ECTS credits</th>
            </tr>
            <tr>
                            <td></td><td></td><td></td>
                            <td><input type="button" name="addCourse" value="Add" id="addCourse"/><input type="button" name="remCourse" value="Remove" id="remCourse" /><input type="hidden" id="courseCount" name="courseCount" value="{$courseCount}" /></td>
                        </tr>
            {iteration:iCourses}
            {$row}
            {/iteration:iCourses}

            
        </table>
    </fieldset>

    <fieldset>

        {option:oDigital}
        <div class="TRdiv">
            <label for="sign"><span>Student's signature: </span></label>
            <img src="{$sourceStud}" alt="students signature" />
             <label for="passwordStud"><span>Confirm signature: </span></label>
            <input class="validate[required,equals[password],custom[onlyLetterNumber]] text-input" type="password" name="passwordStud" id="passwordStud" />
        </div>
        {/option:oDigital}
            
        {option:oPaper}
        <div class="TRdiv">
            <label for="sign"><span>Student's signature: </span></label>
        </div>
        {/option:oPaper}

        <div class="TRdiv">
            <label for="signDate"><span>Date: </span></label>
            <input class="validate[required,custom[date]] text-input" type="text" id="signDate" name="signDate" value="{$signDate|htmlentities}" />
            <span class="req">{$msgSignDate}</span>
        </div>


    </fieldset>
    
    <fieldset>
        <legend>Submit Learning Agreement</legend>
        <p>
            <input type="hidden" name="formAction" id="formRegister" value="doAgree" />
            <input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
        </p>
    </fieldset>

{option:oCoor}
    <fieldset>
        <legend>Sending Institution</legend>
        <p>We confirm that this proposed programme of study/learning agreement is approved.</p>
        
        {option:oDigital}
        <div class="TRdiv">
            <label for="signDepSignSend"><span>Departamental coordinator's signature</span></label>
            <img src="{$sourceDepSend}" alt="signature" >
            <label for="passwordDepSend"><span>Confirm signature: </span></label>
            <input class="validate[required,equals[password],custom[onlyLetterNumber]] text-input" type="password" name="passwordDepSend" id="passwordDepSend" />
        </div>
        {/option:oDigital}
        
        {option:oPaper}
        <div class="TRdiv">
            <label for="signDepSignSend"><span>Departamental coordinator's signature</span></label>
        </div>
        {/option:oPaper}
        
        
        <div class="TRdiv">
            <label for="signDepSignDateSend"><span>Date: </span></label>
            <input class="validate[required,custom[date]] text-input" type="text" id="signDepSignDateSend" name="signDepSignDateSend" value="{$signDepSignDateSend|htmlentities}" />
            <span class="req">{$msgSignDepSignDateSend}</span>
        </div>
        
        {option:oPaper}
        <div class="TRdiv">
            <label for="signInstSignSend"><span>Institutional coordinator's signature</span></label>
            
        </div>
       {/option:oPaper}
        
        {option:oDigital}
        <div class="TRdiv">
            <label for="signInstSignSend"><span>Institutional coordinator's signature</span></label>
            <img src="{$sourceInstSend}" alt="signature" >
             <label for="passwordInstSend"><span>Confirm signature: </span></label>
            <input class="validate[required,equals[password],custom[onlyLetterNumber]] text-input" type="password" name="passwordInstSend" id="passwordInstSend" />
        </div>
        {/option:oDigital}

        <div class="TRdiv">
            <label for="signInstSignDateSend"><span>Date: </span></label>
            <input class="validate[required,custom[date]] text-input" type="text" id="signInstSignDateSend" name="signInstSignDateSend" value="{$signInstSignDateSend|htmlentities}" />
            <span class="req">{$msgSignInstSignDateSend}</span>
        </div>

    </fieldset>

   <fieldset>
        <legend>Receiving Institution</legend>
        <p>We confirm that this proposed programme of study/learning agreement is approved.</p>
        
        {option:oDigital}
        <div class="TRdiv">
            <label for="signRecSignSend"><span>Departamental coordinator's signature</span></label>
            <img src="{$sourceDepRec}" alt="signature" >
            <label for="passwordDepRec"><span>Confirm signature: </span></label>
            <input class="validate[required,equals[password],custom[onlyLetterNumber]] text-input" type="password" name="passwordDepRec" id="passwordDepRec" />
        </div>
        {/option:oDigital}
        
        {option:oPaper}
        <div class="TRdiv">
            <label for="signDepSignRec"><span>Departamental coordinator's signature</span></label>
        </div>
        {/option:oPaper}
        
        
        <div class="TRdiv">
            <label for="signDepSignDateRec"><span>Date: </span></label>
            <input class="validate[required,custom[date]] text-input" type="text" id="signDepSignDateRec" name="signDepSignDateRec" value="{$signDepSignDateRec|htmlentities}" />
            <span class="req">{$msgSignDepSignDateRec}</span>
        </div>
        
        {option:oPaper}
        <div class="TRdiv">
            <label for="signInstSignRec"><span>Institutional coordinator's signature</span></label>
            
        </div>
       {/option:oPaper}
        
        {option:oDigital}
        <div class="TRdiv">
            <label for="signInstSignRec"><span>Institutional coordinator's signature</span></label>
            <img src="{$sourceInstRec}" alt="signature" >
             <label for="passwordInstRec"><span>Confirm signature: </span></label>
            <input class="validate[required,equals[password],custom[onlyLetterNumber]] text-input" type="password" name="passwordInstRec" id="passwordInstRec" />
        </div>
        {/option:oDigital}

        <div class="TRdiv">
            <label for="signInstSignDateRec"><span>Date: </span></label>
            <input class="validate[required,custom[date]] text-input" type="text" id="signInstSignDateRec" name="signInstSignDateRec" value="{$signInstSignDateRec|htmlentities}" />
            <span class="req">{$msgSignInstSignDateRec}</span>
        </div>

    </fieldset>
    
    <div class="TRdiv">
        <textarea class="validate[required],custom[onlyLetterNumber] text-input" type="text" name="coordinator" id="coordinator" cols="50" rows="5">{$coordinator|htmlentities}</textarea>
        <span class="req" id="msgCoordinator">{$msgCoordinator|htmlentities}</span>	
    </div>
    
    <fieldset>
<legend>Submit the Learning Agreement</legend>
        <div class="TRdiv">
		<input type="hidden" name="formAction" id="formRegister" value="doMotivateagree" />
		<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
	</div>
</fieldset>
    {/option:oCoor}

    
</form>
</div>