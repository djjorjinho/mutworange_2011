<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
<form action="" method="post" enctype="multipart/form-data" id="extend" name="extend">
    {option:oDenied}
    <div id="denied">
        <p><strong>Extend Mobility Period has been denied.</strong></p>
        <p><strong>Motivation Home: </strong>{$motivationHome}</p>
        <p><strong>Motivation Host: </strong>{$motivationHost}</p>
        <p>Returned Extend Mobility Period:{$returndExtend}</p>
    </div>
    {/option:oDenied}
    
    {option:oApproved}
    <div id="approved">
        <p><strong>Extend Mobility Period has been approved.</strong></p>
        <p><strong>Motivation Home: </strong>{$motivationHome}</p>
        <p><strong>Motivation Host: </strong>{$motivationHost}</p>
        <p>Returned Extend Mobility Period:{$returndExtend}</p>
    </div>
    {/option:oApproved}
    
    {option:oPending}
    <div id="pending">
        <p><strong>Extend Mobility Period is pending.</strong></p>
    </div>
    {/option:oPending}
<fieldset>
    {$pageJava}
    <legend><h3>Student Information</h3></legend> 

            <p>Family name: {$familyName}</p>
            <p>First name: {$firstName}</p>
            <p>Home institution: {$homeInstitution}</p>
            <p>Education: {$education}</p>   
    <p>
            Host Institution: {$hostinstitution}
    </p>
    <p> Start date: {$startDate}</p>
    <p>End date: {$endDate}</p>
            </fieldset>
    <fieldset>
        <legend><h3>Extend Erasmus period</h3></legend>
     <div class="TRdiv">
            <label for="from"><span>From: </span></label>
            <input class="validate[required, custom[date]] text-input" type="text" id="from" name="from" id="from" value="{$from|htmlentities}"/>
            <span class="req" id="msgFrom">{$msgFrom|htmlentities}</span>	
    </div> 
    <div class="TRdiv">
            <label for="until"><span>Until: </span></label>
            <input class="validate[required,custom[date] text-input" type="text" id="until" name="until" id="until" value="{$until|htmlentities}"/>
            <span class="req" id="msgUntil">{$msgUntil|htmlentities}</span>	
    </div> 
    <div class="TRdiv">
            <label for="months"><span>Total number of months: </span></label>
            <input class="validate[required, custom[integer]] text-input" type="text" name="months" id="months" value="{$months|htmlentities}"/>
            <span class="req" id="msgMonths">{$msgMonths|htmlentities}</span>	
    </div>
    <div class="TRdiv">
        <label for="reason"><span>Reason: </span></label><br />
        <input checked="checked" type="radio" name="reason"  value="exams">Exams<br />
        <input type="radio" name="reason" value="work">Work<br />
        <input type="radio" name="reason" value="other">Other<br />
    </div>
     <div class="TRdiv">
        <label for="notes"><span>Notes:  </span></label>
         <textarea class="validate[textarea] text-input" type="text" rows="5" cols="35" name="notes" id="notes" >{$notes|htmlentities}</textarea>
         <span class="req" id="msgNotes">{$msgNotes|htmlentities}</span>	
    </div>
     
    <div class="TRdiv">
        <label for="signStudent"><span>Signature Student: </span></label>
    </div>
        </fieldset>
    {option:oStudent}
    <fieldset>
        <legend>Student - Submit form</legend>
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
            <div class="TRdiv">
                <label for="printed"><span>Attach signed Mobility Extension Period</span></label>
                <input type="file" class="multi" maxlength="1" accept="pdf" id="signImg" name="pic[]" /><span id="errRegPicture"></span>
            </div>
        <div class="TRdiv">               
        <input type="hidden" name="formAction" id="formExtend" value="doExtend" />
	<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
    </div>
</fieldset>
            {/option:oStudent}

            {option:oHost}
    <fieldset>
        <legend>Host institution - Send to home (if approved)</legend>
       <div class="TRdiv">
        <label for="accepted"><span>Decision: </span></label><br />
        <input type="radio" name="accepted" value="1">Exams<br />
        <input type="radio" name="accepted" value="0">Work<br />
    </div>
      <div class="TRdiv">
        <label for="acceptedReason"><span>Motivation:  </span></label>
         <textarea class="validate[textarea] text-input" type="text" rows="5" cols="35" name="coordinator" id="coordinator" ></textarea>	
    </div>
<div class="TRdiv">
        <label for="signInternationalHome"><span>Signature Host International Office: </span></label>	
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
            <div class="TRdiv">
                <label for="printed"><span>Attach signed Mobility Extension Period</span></label>
                <input type="file" class="multi" maxlength="1" accept="pdf" id="signImg" name="pic[]" /><span id="errRegPicture"></span>
            </div>
        <div class="TRdiv">               
        <input type="hidden" name="formAction" id="formExtend" value="doTohome" />
	<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
    </div>
        </fieldset>
            {/option:oHost}
        {option:oOffice}
    <fieldset>
        <legend>Home institution - Send to host</legend>
       <div class="TRdiv">
        <label for="accepted"><span>Decision: </span></label><br />
        <input type="radio" name="accepted" value="1">Exams<br />
        <input type="radio" name="accepted" value="0">Work<br />
    </div>
      <div class="TRdiv">
        <label for="acceptedReason"><span>Motivation:  </span></label>
         <textarea class="validate[textarea] text-input" type="text" rows="5" cols="35" name="coordinator" id="coordinator" ></textarea>	
    </div>
<div class="TRdiv">
        <label for="signInternationalHome"><span>Signature Host International Office: </span></label>	
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
            <div class="TRdiv">
                <label for="printed"><span>Attach signed Mobility Extension Period</span></label>
                <input type="file" class="multi" maxlength="1" accept="pdf" id="signImg" name="pic[]" /><span id="errRegPicture"></span>
            </div>
        <div class="TRdiv">               
        <input type="hidden" name="formAction" id="formExtend" value="doMotivate" />
	<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
    </div>
        </fieldset>
            {/option:oOffice}
    
</fieldset>
        
</form>
</div>