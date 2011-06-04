<div class="mainDiv">
    {option:Yes}
<form id="form1" action="" method="post" enctype="multipart/form-data" >
        <div class="leftAlDiv">
            <div class="inerDiv">
                <p class="minHead">Arrival and Departure Information : </p> 
                <div class="TRdiv"><p><span class="spleft"><label for="startDate"> Arrival : </label></span><span class="spright">{$startDate}</span></p></div>
                <div class="TRdiv"><p><span class="spleft"><label for="endate"> Departure :  </label></span><span class="spright">{$endDate}</span></p></div>



                <p class="minpar">I agree to pay the rent of one month, one month before my start of the period in order to confirm 
                    my accommodation reservation. At the end of the exchange period the guarantee will be refunded 
                    to students after inspection of the room, at the very latest 14 days after departure.</p>
                <p class="minpar">If I don't pay the deposit at least one month before my start of the period, I won't get a room guaranteed by 
                    {$insName}.<br/> <b>Payment has to be made at : <br/>
                        IBAN : {$iban}<br/>BIC : {$bic}<br/>International bank transfer costs are at your own expenses.</b></p>

                <p class="minpar">Arriving before/Leaving after the reservation period is at your own risk. We do not automatically extend the period of reservation if a student wants to stay longer. </p>
                <p class="minpar">After inspection of the room, {$insName} can refund the deposit by bank transfer on the following account :
                </p>
                <div class="TRdiv"><span class="spleft" >Ac. Holder Name:</span><span class="spright">{$stAcName}</span></div>
                <div class="TRdiv"><span class="spleft">IBAN :</span><span class="spright">{$stAcIban}</span></div>
                <div class="TRdiv" ><span class="spleft">BIC :</span><span class="spright">{$stAcBic}</span></div>

            </div>

        </div>
        <div class="rightAlDiv">
            <p class="minHead">Please select the type of room you prefer :</p>
            {iteration:iResidence}{$resid}{/iteration:iResidence}
    {/option:Yes}
    {option:No}


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

        </div>
    {/option:No}
    </form>
    </div>
</div>