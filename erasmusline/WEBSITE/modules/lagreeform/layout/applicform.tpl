<script type="text/javascript" src="./core/js/jquery/jquery.MultiFile.js"></script>
<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
    <h2>Student Application Form</h2>
    
    {option:oDenied}
    <div id="denied">
        <p><strong>The Student Application Form has been denied.</strong></p>
        <p><strong>Motivation Host: </strong>{$motivationHost}</p>
        <p>Returnd Student Application:{$returndApplic}</p>
    </div>
    {/option:oDenied}
    
    {option:oApproved}
    <div id="approved">
        <p><strong>The Student Application Form has been approved.</strong></p>
        <p><strong>Motivation Host: </strong>{$motivationHost}</p>
        <p>Returnd Student Application:{$returndApplic}</p>
    </div>
    {/option:oApproved}
    
    {option:oPending}
    <div id="pending">
        <p><strong>The Student Application Form is pending.</strong></p>
    </div>
    {/option:oPending}
    

    <form action="" method="post" enctype="multipart/form-data" id="studApplicForm" name="studApplicForm" >

        <fieldset>
            <legend>General info</legend>
            <div class="TRdiv">
                <label for="acaYear"><span>Academic year: </span></label>
                <input class="validate[required] text-input" type="text" id="acaYear" name="acaYear" value="{$acaYear|htmlentities}" />
                <span class="req" id="msgAcaYear">{$msgAcaYear|htmlentities}</span>
            </div>

            {option:oNotFilled}
            <div class="TRdiv">
                <label for="study"><span>Field of Study: </span></label>
                <select name="study" >
                    {iteration:iStudy}
                    {$stud}
                    {/iteration:iStudy}
                </select>
                <span class="req" id="msgStudy">{$msgStudy|htmlentities}</span>
            </div>  
            {/option:oNotFilled}
            {option:oFilled}
            <div class="TRdiv">
                <label for="study"><span>Study: </span></label>
                <input type="text" id="study" name="study" value="{$study|htmlentities}" />
            </div>
            {/option:oFilled}
        </fieldset>

        <fieldset>
            <legend>Sending institution - Contact info</legend>
            <div class="TRdiv">
                <label for="sendInstName"><span>Name: </span></label>
                <input type="text" id="sendInstName" name="sendInstName" value="{$sendInstName|htmlentities}" />
            </div>
            <div class="TRdiv">
                <label for="sendInstAddress"><span>Address: </span></label>
                <input class="validate[required,custom[onlyLetterNumber]] text-input" type="text" id="sendInstAddress" name="sendInstAddress" value="{$sendInstAddress|htmlentities}" />
            </div>

            <div class="TRdiv">
                <p>Type the first letters to get some suggestions.</p>
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

        <fieldset>
            <legend>Student - Contact info</legend>
            <p>To be completed by the student applying.</p>
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
                <label for="sex"><span>Sex: </span></label>
                <input class="validate[required] text-input" type="text" id="sex" name="sex" value="{$sex|htmlentities}" />
            </div>

            <div class="TRdiv">
                <label for="nation"><span>Nationality: </span></label>
                <input class="validate[required] text-input" type="text" id="nation" name="nation" value="{$nation|htmlentities}" />
            </div>

            <div class="TRdiv">
                <label for="birthPlace"><span>Place of Birth: </span></label>
                <input class="validate[required] text-input" type="text" id="birthPlace" name="birthPlace" value="{$birthPlace|htmlentities}" />
            </div>

            <div class="TRdiv">
                <label for="cAddress"><span>Current address: </span></label>
                <input class="validate[required,custom[onlyLetterNumber]] text-input" type="text" id="cAddress" name="cAddress" value="{$cAddress|htmlentities}" />
                <span class="req" id="msgCAddress">{$msgCAddress|htmlentities}</span>
            </div>
            <div class="TRdiv">
                <label for="daateValid"><span>Current address is valid until: </span></label>
                <input class="validate[required,custom[date]] text-input" type="text" id="daateValid" name="daateValid" value="{$daateValid|htmlentities}" />
                <span class="req" id="msgDaateValid">{$msgDaateValid|htmlentities}</span>
            </div>
            <div class="TRdiv">
                <label for="cTel"><span>Tel: </span></label>
                <input class="validate[required,custom[onlyNumberSp]] text-input" type="text" id="cTel" name="cTel" value="{$cTel|htmlentities}" />
                <span class="req" id="msgCTel">{$msgCTel|htmlentities}</span>
            </div>

            <div class="TRdiv">
                <label for="pAddress"><span>Permanent address (if different): </span></label>
                <input class="validate[custom[onlyLetterNumber]] text-input" type="text" id="pAddress" name="pAddress" value="{$pAddress|htmlentities}" />
            </div>
            <div class="TRdiv">
                <label for="pTel"><span>Tel: </span></label>
                <input class="validate[custom[onlyNumberSp]] text-input" type="text" id="pTel" name="pTel" value="{$pTel|htmlentities}" />
            </div>
            <div class="TRdiv">
                <label for="mail"><span>Email: </span></label>
                <input class="validate[custom[email]] text-input" type="text" id="mail" name="mail" value="{$mail|htmlentities}" />
            </div>
        </fieldset>

        <fieldset>
            <legend>Receiving institution - Contact info</legend>
            <div class="TRdiv">
                <p>Type the first letters to get some suggestions.</p>
                <label for="recInstitut"><span>Receiving Institution: </span></label>
                <input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="recInstitut" onkeyup="lookup3(this.value);" onclick="fill3();" name="recInstitut" value="{$recInstitut|htmlentities}" />
                <span class="req" id="msgRecInstitut">{$msgRecInstitut|htmlentities}</span>
            </div>

            <div class="suggestionsBox3" id="suggestions3" style="display: none;">
                <div class="suggestionList3" id="autoSuggestionsList3">
                    &nbsp;
                </div>
            </div>  

            <div class="TRdiv">
                <label for="coountry"><span>Country: </span></label>
                <input class="validate[required,custom[onlyLetterSp]] text-input" type="text" id="coountry" name="coountry" value="{$coountry|htmlentities}" />
                <span class="req" id="msgCoountry">{$msgCoountry|htmlentities}</span>
            </div>
            </fieldset>
        <fieldset>
            <legend>Student - Erasmus info</legend>
            <div class="TRdiv">

                <label for="daateFrom"><span>From: </span></label>
                <input class="validate[required,custom[date]] text-input" type="text" id="daateFrom" name="daateFrom" value="{$daateFrom|htmlentities}" />
                <span class="req" id="msgDaateFrom">{$msgDaateFrom|htmlentities}</span>
            </div>
            <div class="TRdiv">
                <label for="daateUntill"><span>Until: </span></label>
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


            <div class="TRdiv">
                <label for="motivation"><span>Briefly state the reasons why you wish to study abroad</span></label><br />
                <textarea class="validate[required,custom[textarea]" type="text" id="motivation" name="motivation" cols=57" rows="6">{$motivation|htmlentities}</textarea>
                <span class="req" id="msgMotivation">{$msgMotivation|htmlentities}</span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Student - Language competence</legend>
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

            <table id="languageTable">
                <tr>
                    <th>Other languages</th>
                    <th>Currently studying this language</th>
                    <th> Sufficient knowledge to follow lectures</th>
                    <th>Sufficient knowledge to follow lectures if I had some extra preparation</th>
                </tr>
                <tr>
                    <th></th>
                    <th>Yes - No</th>
                    <th>Yes - No </th>
                    <th>Yes - No</th>
                    <th></th>
                </tr>
                {option:oNotFilled}
                <tr>
                    <td><input type="button" name="addLanguage" value="Add" id="addLanguage"/><input type="button" name="removeLanguage" value="Remove" id="remLanguage" /><input type="hidden" id="languageCount" name="languageCount" value="{$languageCount}" /></td>
                </tr>
                {/option:oNotFilled}

                {iteration:iLanguages}
                {$language}
                {/iteration:iLanguages}

            </table>
        </fieldset>

        <fieldset>
            <legend>Student - Work experience related to current study (if relevant)</legend>
            <table id="workTable">
                <tr>
                    <th>Type of work experience</th>
                    <th>Firm/organisation</th>
                    <th>Dates</th>
                    <th>Country</th>
                    <th></th>
                </tr>
                {option:oNotFilled}
                <tr>
                    <td></td><td></td><td></td><td></td>
                    <td><input type="button" name="addWork" value="Add" id="addWork"/><input type="button" name="removeWork" value="Remove" id="remWork" /><input type="hidden" id="workCount" name="workCount" value="{$workCount}" /></td>
                </tr>
                {/option:oNotFilled}

                {iteration:iWorks}
                {$work}
                {/iteration:iWorks}

            </table>
        </fieldset>

        <fieldset>
            <legend>Student - Previous and current study</legend>
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
                <input class="validate[custom[onlyLetterNumber]] text-input" type="text" id="whichInst" name="whichInst" value="{$whichInst|htmlentities}" />
                <span class="req" id="msgWhichInst">{$msgWhichInst|htmlentities}</span>
            </div>
            
            <br />
            <p><strong>The attached Transcript of records includes full details of previous and current higher education study. </strong></p>
            <p><strong>PLEASE ATTACH YOUR TRANSCIPT OF RECORDS WITH PREVIOUS COURSE UNITS TAKEN !</strong></p>
            <p>Must be a PDF</p>

            {option:oNotFilled}
            <div class="TRdiv">
                <label for="pic"><span>Upload your Transcript Of Records here</span></label>
                <input type="file" class="multi" maxlength="1" accept="pdf" id="signImg" name="pic[]" /><span id="errRegPicture"></span>
            </div>
            <div class="TRdiv">
                <input type="hidden" name="formAction" id="formRegister" value="doApplic" />
                <input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
            </div>
            {/option:oNotFilled}

            {option:oFilled}
            <p>{$trrec}</p>
            {/option:oFilled}
        </fieldset>
        
        {option:oCoor}

        <fieldset>
            <legend>Receiving institution - Acknowledgement</legend>
            <p>We hereby acknowledge receipt of the application, the proposed learning agreement and the candidate’s Transcript of records.
            </p>

            <div class="TRdiv">
                <label for="accepted">The above mentioned student is: </label>
                <span>Provisionally accepted at our institution</span><input class="validate[required] radio" type="radio" name="accepted" value="1" id="1" />
                <span>Not accepted at our institution</span><input type="radio" class="validate[required] radio" name="accepted" value="0" id="0"  />
            </div>

            <div class="TRdiv">
                <label for="coordinator">Motivation</label>
                <textarea class="validate[required],custom[onlyLetterNumber] text-input" type="text" name="coordinator" id="coordinator" cols="50" rows="5"></textarea>	
            </div>

            <div class="TRdiv">
                <label for="signDepSign"><span>Departamental coordinator's signature</span></label>
                <select name="HostDepCoor" >
               {iteration:iDepCoor}
                     {$depCoor}
               {/iteration:iDepCoor}
            </select>
            </div>

            <div class="TRdiv">
                <label for="signDepSignDate"><span>Date: </span></label>
                <input class="validate[required,custom[date]] text-input" type="text" id="signDepSignDate" name="signDepSignDate" />
            </div>


            <div class="TRdiv">
                <label for="signInstSign"><span>Institutional coordinator's signature</span></label>
            </div>


            <div class="TRdiv">
                <label for="signInstSignDate"><span>Date: </span></label>
                <input class="validate[required,custom[date]] text-input" type="text" id="signInstSignDate" name="signInstSignDate"  />
            </div>

            <script language="javascript">
             function printpage()
              {
               window.print();
              }
            </script>
            <div class="TRdiv">
                <label for="print">Print dit formulier af</label>
                <input type="button" value="Print" onclick="printpage();" />
            </div>
        </fieldset>
        {/option:oCoor}

        {option:oOffice}
        <fieldset>
            <legend>Sending Institution - Send to host</legend>
            <p><strong>Strong advisible to check for any mistakes.</strong></p>
            <div class="TRdiv">
                <input type="hidden" name="formAction" id="formRegister" value="doTohostapplic" />
                <input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
            </div>
            
        </fieldset>
        
        {/option:oOffice}

        {option:oHost}
        
        <fieldset>
            <legend>Receiving institution - Send to Home institute</legend>
            <div class="TRdiv">
                <label for="printed"><span>Attach signed Application Form</span></label>
                <input type="file" class="multi" maxlength="1" accept="pdf" id="signImg" name="pic[]" /><span id="errRegPicture"></span>
            </div>
            <div class="TRdiv">
                <input type="hidden" name="formAction" id="formRegister" value="doMotivateapplic" />
                <input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
            </div>
        </fieldset>

        {/option:oHost}

</div>
