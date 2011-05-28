<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
<form action="" method="post" enctype="multipart/form-data" id="extend" name="extend">	
<fieldset>
    {$pageJava}
    <legend>Student Information</legend> 
    <br />
    <div class="TRdiv">
        <ul>
            <li>Family name: {$familyName}</li>
            <li>First name: {$firstName}</li>
            <li>Home institution: {$homeInstitution}</li>
            <li>Education: {$education}</li>            
        </ul><br />       
    </div>
    <div class="TRdiv">
        <ul>
            <li>Host Institution : {$hostinstitution}</li>
        </ul><br />
    </div>
    <div class="TRdiv">
        <ul>
            <li>Start date: {$startDate}</li>
            <li>End date: {$endDate}</li>
        </ul><br />
    </div>    
     <div class="TRdiv">
        <p>Extend Erasmus period</p> 
            <label for="from"><span>From : </span></label>
            <input class="validate[required] text-input" type="text" id="from" name="from" id="from" value="{$from|htmlentities}"/>
            <span class="req" id="msgFrom">{$msgFrom|htmlentities}</span>	
    </div> 
    <div class="TRdiv">
            <label for="until"><span>Until : </span></label>
            <input class="validate[required] text-input" type="text" id="until" name="until" id="until" value="{$until|htmlentities}"/>
            <span class="req" id="msgUntil">{$msgUntil|htmlentities}</span>	
    </div> 
    <div class="TRdiv">
            <label for="months"><span>Total number of months : </span></label>
            <input class="validate[required] text-input" type="text" name="months" id="months" value="{$months|htmlentities}"/>
            <span class="req" id="msgMonths">{$msgMonths|htmlentities}</span>	
    </div>
    <div class="TRdiv">
        <label for="reason"><span>Reason : </span></label><br />
        <input type="radio" name="reason"  value="exams">Exams<br />
        <input type="radio" name="reason" value="work">Work<br />
        <input type="radio" name="reason" value="other">Other<br />
    </div>
     <div class="TRdiv">
        <label for="notes"><span>Notes :  </span></label>
         <textarea class="validate[required] text-input" type="text" rows="5" cols="35" name="notes" id="notes" >{$notes|htmlentities}</textarea>
         <span class="req" id="msgNotes">{$msgNotes|htmlentities}</span>	
    </div>
    <div class="TRdiv">
        <label for="signInternationalHost"><span>Signature Host International Office : </span></label>
         <input class="validate[required] text-input" type="text" name="signInternationalHost" id="signInternationalHost" value="{$signInternationalHost|htmlentities}"/>
         <span class="req" id="msgSignInternationalHost">{$msgSignInternationalHost|htmlentities}</span>	
    </div>
     <div class="TRdiv">
        <label for="signInternationalHome"><span>Signature Home International Office : </span></label>
         <input class="validate[required] text-input" type="text" name="signInternationalHome" id="signInternationalHome" value="{$signInternationalHome|htmlentities}"/>
         <span class="req" id="msgSignInternationalHome">{$msgSignInternationalHome|htmlentities}</span>	
    </div>
    <div class="TRdiv">
        <label for="signStudent"><span>Signature Student : </span></label>
         <input class="validate[required] text-input" type="text" name="signStudent" id="signStudent" value="{$signStudent|htmlentities}"/>
         <span class="req" id="msgSignStudent">{$msgSignStudent|htmlentities}</span>	
    </div>
       <div class="TRdiv">
        <label for="Decision"><span>Decision : </span></label><br />
        <input type="radio" name="Decision" value="accepted">Exams<br />
        <input type="radio" name="Decision" value="denied">Work<br />
    </div>
      <div class="TRdiv">
        <label for="acceptedReason"><span>Reasons :  </span></label>
         <textarea class="validate[required] text-input" type="text" rows="5" cols="35" name="acceptedReason" id="acceptedReason" >{$acceptedReason|htmlentities}</textarea>
         <span class="req" id="msgAcceptedReason">{$msgAcceptedReason|htmlentities}</span>	
    </div>
        <div class="TRdiv">               
        <input type="hidden" name="formAction" id="formExtend" value="doSubmit" />
	<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
    </div>
        
    
</fieldset>
</form>
</div>