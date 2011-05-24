<div class="mainDiv">
<form action="" method="post" enctype="multipart/form-data" id="precandidate" name="precandidate">	
<fieldset>
    {$pageJava}
    <legend>Student Information</legend>    
    <div class="TRdiv">
        <label for="familyName"><span>Last Name : </span></label>
        <input type="text" name="familyName" id="familyName" value="{$familyName|htmlentities}" />
    </div>
    <div class="TRdiv">
        <label for="firstName"><span>First Name : </span></label>
        <input type="text" name="firstName" id="firstName" value="{$firstName|htmlentities}" />
    </div> 
    <div class="TRdiv">
        <label for="email"><span>Email address : </span></label>
        <input type="text" name="email" id="email" value="{$email|htmlentities}" />  
    </div>   
     <div class="TRdiv">
        <label for="instName"><span>University : </span></label>
        <input type="text" name="instName" id="instName" value="{$instName|htmlentities}" />  
    </div>     
    <div class="TRdiv">
        <label for="streetNr"><span>Street + NR : </span></label>
        <input type="text" name="streetNr" id="streetNr" value="{$streetNr|htmlentities}" />
    </div>   
    <div class="TRdiv">
        <label for="tel"><span>Telephone : </span></label>
        <input type="text" name="tel" id="tel" value="{$tel|htmlentities}" />
    </div>
    <div class="TRdiv">
        <label for="mobilePhone"<span>Mobile Phone : </span></label>
        <input type="text" name="mobilePhone" id="mobilePhone" value="{$mobilePhone|htmlentities}" />
    </div>
</fieldset>

{option:oFilled}    
<fieldset>    
    <legend>Study</legend> 
    <div class="TRdiv">
        <label for="study"><span>Study :  </span></label>
        <input type="text" name="study" id="study" value="{$study|htmlentities}" />
    </div> 
</fieldset>
    <fieldset>
    <legend>Preferred countries</legend> 
    <div class="TRdiv">
        <label for="choice1"><span>First Choice : </span></label>
        <input type="text" name="choice1" id="firstChoice" value="{$choice1|htmlentities}" />
    </div>
    <div class="TRdiv">
        <label for="choice2"><span>Second Choice : </span></label>
        <input type="text" name="choice2" id="secondChoice" value="{$choice2|htmlentities}" />
	</div>  
    <div class="TRdiv">
        <label for="choice3"><span>Third Choice : </span></label>
        <input type="text" name="choice3" id="choice3" value="{$choice3|htmlentities}" />
    </div> 
    </fieldset>
    <fieldset>
    <legend>CV</legend>    
      <div class="TRdiv">
        <label for="cv"><span>Upload your CV here :</span></label>        
        <input type="text" name="cv" id="cv" value="{$cv|htmlentities}" />
    </div>
</fieldset>
<fieldset>
    <legend>Transcript of records</legend>    
      <div class="TRdiv">
        <label for="transcript"><span>Upload your Transcript of Records here :</span></label>
        <input type="text" name="transcript" id="transcript" value="{$transcript|htmlentities}" />
    </div>
</fieldset>
<fieldset>
    <legend>Certificate of Foreign Language</legend>    
      <div class="TRdiv">
        <label for="certificate"><span>Upload your Certificate of Foreign Language here :</span></label>
        <input type="text" name="certificate" id="certificate" value="{$certificate|htmlentities}" />
        
    </div>
</fieldset>
<fieldset>
    <legend>Extra</legend>        
     <div class="TRdiv">
            <label for="traineeOrStudy"><span>Aanvraag voor: </span></label>            
            <input type="text" name="traineeOrStudy" id="traineeOrStudy" value="{$traineeOrStudy|htmlentities}" />
    </div>
    <div class="TRdiv">
            <label for="cribb"><span>Op kot : </span></label>
            <input type="text" name="cribb" id="cribb" value="{$cribb|htmlentities}" />
    </div>
    <div class="TRdiv">
            <label for="cribRent"><span>Wens je je kot over te laten tijdens je uitwissling : </span></label>
            <input type="text" name="cribRent" id="cribRent" value="{$cribRent|htmlentities}" />
    </div>
   <div class="TRdiv">
            <label for="scolarship"><span>Deelnemen zonder Europese subsidie : </span></label>
            <input type="text" name="scolarship" id="scolarship" value="{$scolarship|htmlentities}" />
    </div>
</fieldset> 
{/option:oFilled}
{option:oNotFilled}
    <fieldset>
    <legend>Study</legend>    
    <div class="TRdiv">
        <label for="study"><span>Study :</span></label>
        <select name="study" >
               {iteration:iStudy}
                     {$stud}
               {/iteration:iStudy}
            </select>
            <span class="req" id="msgStudy">{$msgStudy|htmlentities}</span>    
</fieldset>
    
    <fieldset>    
    <legend>Preferred countries</legend>    
    <div class="TRdiv">
        <label for="choice1"><span>First Choice : </span></label>
        <select name="choice1" >
               {iteration:iChoice1}
                     {$choic1}
               {/iteration:iChoice1}
            </select>
            <span class="req" id="msgChoice1">{$msgChoice1|htmlentities}</span>
            
    <div class="TRdiv">
        <label for="choice2"><span>Second Choice : </span></label>
        <select name="choice2"  >
               {iteration:iChoice2}
                     {$choic2}
               {/iteration:iChoice2}
            </select>
            <span class="req" id="msgChoice2">{$msgChoice2|htmlentities}</span>
	</div>  
    <div class="TRdiv">
        <label for="choice3"><span>Third Choice : </span></label>
        <select name="choice3" >
               {iteration:iChoice3}
                     {$choic3}
               {/iteration:iChoice3}
            </select>
            <span class="req" id="msgChoice3">{$msgChoice3|htmlentities}</span>
	</div> 
 </fieldset>
    <fieldset>
    <legend>CV</legend>    
      <div class="TRdiv">
        <label for="cv"><span>Upload your CV here :</span></label>
        <input type="file" class="multi" maxlength="1" accept="pdf" id="cv" name="pic[]" /><span id="errRegPicture"></span>
    </div>
</fieldset>
<fieldset>
    <legend>Transcript of records</legend>    
      <div class="TRdiv">
        <label for="transcript"><span>Upload your Transcript of Records here :</span></label>
        <input type="file" class="multi" maxlength="1" accept="pdf" id="transcript" name="pic[]" /><span id="errRegPicture"></span>
    </div>
</fieldset>
<fieldset>
    <legend>Certificate of Foreign Language</legend>    
      <div class="TRdiv">
        <label for="certificate"><span>Upload your Certificate of Foreign Language here :</span></label>
        <input type="file" class="multi" maxlength="1" accept="pdf" id="certificate" name="pic[]" /><span id="errRegPicture"></span>
    </div>
</fieldset>
<fieldset>
    <legend>Extra</legend>        
     <div class="TRdiv">
            <label for="traineeOrStudy"><span>Aanvraag voor: </span></label>
            <select name="traineeOrStudy" value="{$traineeOrStudy}" >
               {iteration:iDemand}
                     {$demand}
               {/iteration:iDemand}
            </select>
            <span class="req" id="msgTraineeOrStudy">{$msgTraineeOrStudy|htmlentities}</span>
    </div>
    <div class="TRdiv">
            <label for="cribb"><span>Op kot : </span></label>
            <span>Yes</span><input type="radio" {$cribYes} id="cribYes" name="cribb" value="Yes" class="validate[required] radio"  />
            <span>No</span><input type="radio" {$cribNo} id="cribNo" name="cribb" value="No" class="validate[required] radio" />
            <span class="req" id="msgCribb">{$msgCribb|htmlentities}</span>
    </div>
    <div class="TRdiv">
            <label for="cribRent"><span>Wens je je kot over te laten tijdens je uitwissling : </span></label>
            <span>Yes<input type="radio" id="rentYes" {$rentYes} name="cribRent" value="Yes" class="validate[required] radio" /></span>
            <span>No<input type="radio" id="rentNo" {$rentNo} name="cribRent" value="No" class="validate[required] radio" /></span>
            <span class="req" id="msgCribRent">{$msgCribRent|htmlentities}</span>
    </div>
   <div class="TRdiv">
            <label for="scolarship"><span>Deelnemen zonder Europese subsidie : </span></label>
            <span>Yes<input type="radio" id="scolYes" {$scolYes} name="scolarship" value="Yes" class="validate[required] radio" /></span>
            <span>No<input type="radio" id="scolNo" {$scolNo} name="scolarship" value="No" class="validate[required] radio" /></span>
            <span class="req" id="msgScolarship">{$msgScolarship|htmlentities}</span>
    </div>
</fieldset>    
{/option:oNotFilled}
<fieldset>
    <legend>Motivation</legend>    
    <div class="TRdiv">
        <textarea class="validate[required],custom[onlyLetterNumber] text-input" type="text" name="motivation" id="motivation" cols="50" rows="5">{$motivation|htmlentities}</textarea>
        <span class="req" id="msgMotivation">{$msgMotivation|htmlentities}</span>	
    </div>
</fieldset>
{option:oBelgium}
{/option:oBelgium}
{option:oPortugal}
<fieldset>
    <legend>Portugal</legend>      
</fieldset>
{/option:oPortugal}    
{option:oBulgary}
<fieldset>
    <legend>Bulgary</legend>      
</fieldset>
{/option:oBulgary} 
{option:oGermany}
<fieldset>
    <legend>Germany</legend>      
</fieldset>
{/option:oGermany}    
{option:oGreece}
<fieldset>
    <legend>Greece</legend>      
</fieldset>
{/option:oGreece}
{option:oIceland}
<fieldset>
    <legend>Iceland</legend>      
</fieldset>
{/option:oIceland}
    
{option:oNotFilled}
    <div class="TRdiv">               
        <input type="hidden" name="formAction" id="formCert" value="doSubmit" />
	<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
    </div>   
{/option:oNotFilled}
    
{option:oCoor}
<fieldset>
    <legend>Motivation</legend>    
    <div class="TRdiv">
        <textarea class="validate[required],custom[onlyLetterNumber] text-input" type="text" name="coordinator" id="coordinator" cols="50" rows="5">{$coordinator|htmlentities}</textarea>
        <span class="req" id="msgCoordinator">{$msgCoordinator|htmlentities}</span>	
    </div>
    <div class="TRdiv">
<label for="approve"><span>Approve or Deny: </span></label>
            <span>Yes</span><input class="validate[required] radio" type="radio"  name="approve" value="1" id="yes"  />
            <span>No</span><input class="validate[required] radio" type="radio"  name="approve" value="0" id="no"  />
            <span class="req" id="msgApprove">{$msgApprove|htmlentities}</span>
</div>
    <div class="TRdiv">               
        <input type="hidden" name="formAction" id="formPre" value="doMotivate" />
	<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
    </div>
</fieldset>
{/option:oCoor}
</form>