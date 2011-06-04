<div class="mainDiv">
    {$errorString}

    {option:showSelectAccommodationYN}

    <form id="form1" name="form1" action="" method="post" enctype="multipart/form-data" >

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
        <div class="leftAlDiv">
            <div class="inerDiv">
                <p class="minHead">Arrival and Departure Information : </p> 
                <div class="TRdiv"><p><span class="spleft"><label for="startDate"> Arrival : </label></span><span class="spright"><input type="text" name="startDate"  id="startDate" class="validate[required,custom[date]] text-input"  /></span></p></div>
                <div class="TRdiv"><p><span class="spleft"><label for="endate"> Departure :  </label></span><span class="spright"><input type="text" name="endDate"  id="endate" class="validate[required,custom[date]] text-input"  /></span></p></div>



                <p class="minpar">I agree to pay the rent of one month, one month before my start of the period in order to confirm 
                    my accommodation reservation. At the end of the exchange period the guarantee will be refunded 
                    to students after inspection of the room, at the very latest 14 days after departure.</p>
                <p class="minpar">If I don't pay the deposit at least one month before my start of the period, I won't get a room guaranteed by 
                    {$insName}.<br/> <b>Payment has to be made at : <br/>
                        IBAN : {$iban}<br/>BIC : {$bic}<br/>International bank transfer costs are at your own expenses.</b></p>

                <p class="minpar">Arriving before/Leaving after the reservation period is at your own risk. We do not automatically extend the period of reservation if a student wants to stay longer. </p>
                <p class="minpar">After inspection of the room, {$insName} can refund the deposit by bank transfer on the following account :
                </p>
                <div class="TRdiv"><span class="spleft" >Ac. Holder Name:</span><span class="spright"><input class="validate[required]" type="text" name="stAcName"  id="stAcName"/></span></div>
                <div class="TRdiv"><span class="spleft">IBAN :</span><span class="spright"><input class="validate[required]" type="text" name="stAcIban"  id="iban"/></span></div>
                <div class="TRdiv" ><span class="spleft">BIC :</span><span class="spright"><input class="validate[required]" type="text" name="stAcBic"  id="bic"/></span></div>

            </div>

        </div>
        <div class="rightAlDiv">
            <p class="minHead">Please select the type of room you prefer :</p>
            {iteration:iResidence}{$resid}{/iteration:iResidence}
        </div>



        <div class="alCenterDiv"><p><input type="hidden" name="formAction" id="formValidate" value="doSubmit" />
                <input class="button" name="postForm" id="postForm" type="submit" value="Submit Form"/></p>
        </div>  
    </form>
    {/option:showSelectAccomodation}

    {option:showAccomNo}
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

            <p><b>I confirm that I don't want to make a reservation for a student room.</b></p>
            <p><input type="hidden" name="formAction" id="formValidate" value="doSubmitno" />
                <input class="button" name="postForm" id="postForm" type="submit" value="Submit"/></p>

        </div>
    </form>
    {/option:showAccomNo}


    {option:showComplete}
        {$success}
    {/option:showComplete}
</div>