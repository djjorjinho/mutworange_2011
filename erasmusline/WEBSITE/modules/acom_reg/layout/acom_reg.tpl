{$errorString}

{option:showSelectAccommodationYN}

<form id="form1" action="" method="post" enctype="multipart/form-data" >





    <div class="leftAlDiv">
        
        <p class="minHead">Student Information</p>
          <div class="TRdiv"><span class="spleft">First Name :</span><span class="spright">{$stFirstName}</span></div>
        <div class="TRdiv"><span class="spleft">Last Name :</span><span class="spright">{$stLastName}</span></div>
        <div class="TRdiv"><span class="spleft">Gender :</span><span class="spright">{$stGender}</span></div>
        <div class="TRdiv"><span class="spleft">E-mail :</span><span class="spright">{$stMail}</span></div>
    </div>
    <div class="rightAlDiv">


<div><p class="minHead">Sending Institution Information</p></div>
        <div class="TRdiv"><span class="spleft">Institution Name :</span><span class="spright">{$seInName}</span></div>
 <div><p class="minHead">Receiving Institution Information</p></div>
        <div class="TRdiv"><span class="spleft">Institution Name :</span><span class="spright">{$reInName}</span></div>
        </div>
  
           

    <div class="alCenterDiv">
                <p class="minHead">Accommodation</p>

        <input class="validate[required]" type="radio" id="radio1" name="option1" value="nocon"/><label for="radio1">I confirm that I don't want to make a reservation for a student room.</label><br/>
        <input class="validate[required]"  type="radio" id="radio2" name="option1" value="con"/><label for="radio2">I confirm that I want to make a reservation for a student room,<br/>organized by the student accommodation office and I agree with the following stipulations</label>
     <p><input type="hidden" name="formAction" id="formValidate" value="doNext" />
            <input class="button" name="postForm" id="postForm" type="submit" value="Next"/></p>
    
    </div>

   </form>
   
{/option:showSelectAccommodationYN}
{option:showSelectAccomodation}
<form id="form1" action="" method="post" enctype="multipart/form-data" >
    <p>The rooms are available for reservation from:</p> 
    <select id="option" class="validate[required]" ><option></option><option value="1">February / March until June</option><option value="2">September / October until January</option></select>
    

 
    <div class="TRdiv"><span class="spleft"><label for="date"> Arrival :  </label</span><span class="spright"><input type="text" name="startDate"  id="startDate" class="validate[required,custom[date]] text-input"  /></span></div>

    <div class="TRdiv"><label for="date"> Departure :  </label><input type="text" name="endate"  id="endate" class="validate[required,custom[date]] text-input"  /></div>


<p>- It is very important to state your arrival and departure day, the room reservations will happen for the period indicated.</p>

<p>- There are 3 different categories.  Choose one from the below :</p>


Please select the type of room you prefer :
<select>
 <option value=""></option>
 
</select>



<p>- If I don't pay the deposit at least one month before my start of the period, I won't get a room.</p>

<p>-  :</p>



 
   <p>&nbsp;</p>  <p>&nbsp;</p>





    <div align="center"><p><input type="hidden" name="formAction" id="formValidate" value="doShit" />
            <input class="button" name="postForm" id="postForm" type="submit" value="Submit Form"/></p>
    </div>  
</form>
{/option:showSelectAccomodation}