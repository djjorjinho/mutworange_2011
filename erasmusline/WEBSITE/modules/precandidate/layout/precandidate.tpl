<div class="mainDiv">
<form action="" method="post" enctype="multipart/form-data" id="precandidate">	
<fieldset>
    <legend>Student Information</legend>    
    <div class="TRdiv">
        <label for="familyName"><span>Last Name : </span></label>
        <input type="text" name="familyName" id="familyName" value="{$familyName|htmlentities}" disabled="true" />
    </div>
    <div class="TRdiv">
        <label for="firstName"><span>First Name : </span></label>
        <input type="text" name="firstName" id="firstName" value="{$firstName|htmlentities}" disabled="true" />
    </div> 
    <div class="TRdiv">
        <label for="email"><span>Email address : </span></label>
        <input type="text" name="email" id="email" value="{$email|htmlentities}" disabled="true" />  
    </div>   
     <div class="TRdiv">
        <label for="instName"><span>University : </span></label>
        <input type="text" name="instName" id="instName" value="{$instName|htmlentities}" disabled="true" />  
    </div>     
    <div class="TRdiv">
        <label for="streetNr"><span>Street + NR : </span></label>
        <input type="text" name="streetNr" id="streetNr" value="{$streetNr|htmlentities}" disabled="true" />
    </div>   
    <div class="TRdiv">
        <label for="tel"><span>Telephone : </span></label>
        <input type="text" name="tel" id="tel" value="{$tel|htmlentities}" disabled="true" />
    </div>
    <div class="TRdiv">
        <label for="mobilePhone"<span>Mobile Phone : </span></label>
        <input type="text" name="mobilePhone" id="mobilePhone" value="{$mobilePhone|htmlentities}" disabled="true" />
    </div>
</fieldset>
<fieldset>
    <legend>Study</legend>    
    <div class="TRdiv">
        <label for="study"><span>Study :</span></label>
        <input class="validate[required],custom[onlyLetterSp] text-input" type="text" name="study" id="study" value="{$study|htmlentities}"/>
        <span class="req" id="msgStudy">{$msgStudy|htmlentities}</span>	
    </div>
</fieldset>
<fieldset>
    <legend>Preferred countries</legend>    
    <div class="TRdiv">
        <label for="firstChoice"><span>First Choice : </span></label>
        <input class="validate[required],custom[onlyLetterSp] text-input" type="text" name="firstChoice" id="firstChoice" value="{$firstChoice|htmlentities}"/>
        <span class="req" id="msgFirstChoice">{$msgFirstChoice|htmlentities}</span>	
    </div>
    <div class="TRdiv">
        <label for="secondChoice"><span>Second Choice :</span></label>
        <input class="validate[required],custom[onlyLetterSp] text-input" type="text" name="secondChoice" id="secondChoice" value="{$secondChoice|htmlentities}"/>
        <span class="req" id="msgSecondChoice">{$msgSecondChoice|htmlentities}</span>	
    </div>  
    <div class="TRdiv">
        <label for="thirdChoice"><span>Third Choice :</span></label>
        <input class="validate[required],custom[onlyLetterSp] text-input" type="text" name="thirdChoice" id="thirdChoice" value="{$thirdChoice|htmlentities}"/>
        <span class="req" id="msgThirdChoice">{$msgThirdChoice|htmlentities}</span>	
    </div>
</fieldset>
<fieldset>
    <legend>Motivation</legend>    
    <div class="TRdiv">
        <textarea class="validate[required],custom[onlyLetterNumber] text-input" type="text" name="motivation" id="motivation" cols="50" rows="5">{$motivation|htmlentities}</textarea>
        <span class="req" id="msgMotivation">{$msgMotivation|htmlentities}</span>	
    </div>
</fieldset>
<fieldset>
    <legend>CV</legend>    
      <div class="TRdiv">
        <label for="cv"><span>Upload your CV here :</span></label>
        <input type="file" class="validate[custom[onlyPdf]]" name="cv" id="cv" value="{$cv|htmlentities}"/>
        <span class="req" id="msgCv">{$msgCv|htmlentities}</span>	
    </div>
</fieldset>
<fieldset>
    <legend>Transcript of records</legend>    
      <div class="TRdiv">
        <label for="transcript"><span>Upload your Transcript of Records here :</span></label>
        <input type="file" class="validate[custom[onlyPdf]]" name="transcript" id="transcript" value="{$transcript|htmlentities}"/>
        <span class="req" id="msgTranscript">{$msgTranscript|htmlentities}</span>	
    </div>
</fieldset>
<fieldset>
    <legend>Certificate of Foreign Language</legend>    
      <div class="TRdiv">
        <label for="certificate"><span>Upload your Certificate of Foreign Language here :</span></label>
        <input type="file" class="validate[custom[onlyPdf]]" name="certificate" id="certificate" value="{$certificate|htmlentities}"/>
        <span class="req" id="msgCertificate">{$msgCertificate|htmlentities}</span>	
    </div>
</fieldset>
{option:oBelgium}
<fieldset>
    <legend>Belgium</legend>        
     <div class="TRdiv">
            <label for="aanvraag"><span>Aanvraag voor: </span></label>
            <select name="aanvraag" value="{$selectedAanvraag}" >
               {iteration:iAanvraag}
                     {$aanvraag}
               {/iteration:iAanvraag}
            </select>
            <span class="req" id="msgAanvraag">{$msgAanvraag|htmlentities}</span>
    </div>
    <div class="TRdiv">
            <label for="kot"><span>Op kot : </span></label>
            <span>Ja</span><input type="radio" {$kotTrue} id="kotJa" name="kot" value="1" class="validate[required] radio"  />
            <span>Nee</span><input type="radio" {$kotFalse} id="kotNee" name="kot" value="0" class="validate[required] radio" />
            <span class="req" id="msgKot">{$msgKot|htmlentities}</span>
    </div>
    <div class="TRdiv">
            <label for="kotOverlaten"><span>Wens je je kot over te laten tijdens je uitwissling : </span></label>
            <span>Ja<input type="radio" id="kotOverlatenJa" {$kotOverlatenTrue} name="kotOverlaten" value="1" class="validate[required] radio" /></span>
            <span>Nee<input type="radio" id="kotOverlatenNee" {$kotOverlatenFalse} name="kotOverlaten" value="0" class="validate[required] radio" /></span>
            <span class="req" id="msgKotOverlaten">{$msgKotOverlaten|htmlentities}</span>
    </div>
   <div class="TRdiv">
            <label for="subsidie"><span>Deelnemen zonder Europese subsidie : </span></label>
            <span>Ja<input type="radio" id="subdidieJa" {$subsidieTrue} name="subsidie" value="1" class="validate[required] radio" /></span>
            <span>Nee<input type="radio" id="subsidieNee" {$subsidieFalse} name="subsidie" value="0" class="validate[required] radio" /></span>
            <span class="req" id="msgSubsidie">{$msgSubsidie|htmlentities}</span>
    </div>
</fieldset>
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
    <div class="TRdiv">               
        <input type="hidden" name="formAction" id="formCert" value="doSubmit" />
	<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
    </div>    
</form>