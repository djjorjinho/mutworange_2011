<style>


.accomodationList{
    
}
.aHead {
padding-top: 15px;
padding-left: 10px;
    font-size: medium;


}
.spleft label{
    vertical-align: middle;
}



.TRDiv table {
    text-align: center;
}

.TRdiv input{
    font-size:12px;
    border:solid 1px #aacfe4;


}


/******* Transip Records *******/
div.curFocus {
    background: #a9ef96;
}

.minpar {
    padding-top: 10px;
    padding-left: 10px;
    width: 80%;
}

/* CSS for Transcript Of Records (select.tpl & vselect.tpl)*/
.selDiv {
    text-align: center;
}
.selDiv th tr {
font-size: small;
}






/* CSS for Transcript Of Records (main.tpl & mainRecords.tpl)*/
.spright {
    padding-left: 150px;
    font-size:small;
    font-size: 15px;

}
.spleft {
    display: block;
position:absolute;
width: 130px;
text-align: right;
   
}
.minHead {
padding-top: 15px;
padding-left: 10px;
    font-size: medium;


}
.rightAlDiv {
    width:425px;
    position: relative;

    float: right;




}
.leftAlDiv {
    width:425px;
    position: relative;
    float: left;


}
.alCenterDiv {
    position: relative;
text-align: center;
    display: inline-block;
    width: 100%;


}

.min {
    
    width:200px;
    
    
}
.buttonPanel {
    display:inline-block;
 text-align: center;
    width:100%;

    vertical-align: middle;
}
.small {
text-align: left;
float: left;
width: 150px; 
    border-bottom-style: solid;
    border-left-style: solid;
    border-right-style: solid;
    border-bottom-width: 2px;
    border-left-width: 1px;
    border-right-width: 1px;
    border-right-color: #00ff00;
    border-left-color: #00ff00;
    border-bottom-color: #00ff00;
    border-top-color: #00ff00;
    vertical-align: middle;
}

/* Sortable tables */
table.sortable thead {
    background-color:#eee;
    font-weight: bold;
    cursor: default;
}



acomDiv {
        display:block;
background: #000; 

}
</style>
<div style="display: inline-block;font-family: Arial,  Verdana, Helvetica, sans-serif;
    font-size:12px;width:100%;
    background:#ebf4fb;
    width:850px;">
    <h1 align="center">Accomodation Register</h1>
    <form id="form1" action="" method="post" enctype="multipart/form-data" >
    
    <div style="display: inline-block;">
        <div style="width:425px;
    position: relative;
    float: left;">

        <p style="padding-top: 15px;
padding-left: 10px;
font-size: medium;
">Student Information</p>
        <div class="TRdiv"><span style="display: block;
position:absolute;
width: 130px;
text-align: right;">First Name :</span><span style="padding-left: 150px;
    font-size:small;
    font-size: 15px;">{$stFirstName}</span></div>
        <div class="TRdiv"><span style="display: block;
position:absolute;
width: 130px;
text-align: right;">Last Name :</span><span style="padding-left: 150px;
    font-size:small;
    font-size: 15px;">{$stLastName}</span></div>
        <div class="TRdiv"><span style="display: block;
position:absolute;
width: 130px;
text-align: right;">Gender :</span><span style="padding-left: 150px;
    font-size:small;
    font-size: 15px;">{$stGender}</span></div>
        <div class="TRdiv"><span style="display: block;
position:absolute;
width: 130px;
text-align: right;">E-mail :</span><span style="padding-left: 150px;
    font-size:small;
    font-size: 15px;">{$stMail}</span></div>
    </div>
    <div style="width:425px;position: relative;float: right;">


        <div><p style="padding-top: 15px;
padding-left: 10px;
font-size: medium;
">Sending Institution Information</p></div>
        <div class="TRdiv"><span style="display: block;
position:absolute;
width: 130px;
text-align: right;">Institution Name :</span><span style="padding-left: 150px;
    font-size:small;
    font-size: 15px;">{$seInName}</span></div>
        <div><p style="padding-top: 15px;
padding-left: 10px;
font-size: medium;
">Receiving Institution Information</p></div>
        <div class="TRdiv"><span style="display: block;
position:absolute;
width: 130px;
text-align: right;">Institution Name :</span><span style="padding-left: 150px;
    font-size:small;
    font-size: 15px;">{$reInName}</span></div>
    </div>
    </div>
        <div style="display: inline-block;">

    
    <div style="width:425px;
    position: relative;
    float: left;">
    <p style="padding-top: 15px;
padding-left: 10px;
font-size: medium;
">Arrival and Departure Information : </p> 
    <div class="TRdiv"><p><span style="display: block;
position:absolute;
width: 130px;
text-align: right;"><label for="startDate"> Arrival : </label></span><span style="padding-left: 150px;
    font-size:small;
    font-size: 15px;">{$startDate}</span></p></div>
    <div class="TRdiv"><p><span style="display: block;
position:absolute;
width: 130px;
text-align: right;"><label for="endate"> Departure :  </label></span><span style="padding-left: 150px;
    font-size:small;
    font-size: 15px;">{$endDate}</span></p></div>

   
     
    <p class="minpar">I agree to pay the rent of one month, one month before my start of the period in order to confirm 
        my accommodation reservation. At the end of the exchange period the guarantee will be refunded 
        to students after inspection of the room, at the very latest 14 days after departure.</p>
    <p class="minpar">If I don't pay the deposit at least one month before my start of the period, I won't get a room guaranteed by 
        {$insName}.<br/> <b>Payment has to be made at : <br/>
    IBAN : {$iban}<br/>BIC : {$bic}<br/>International bank transfer costs are at your own expenses.</b></p>

    <p class="minpar">Arriving before/Leaving after the reservation period is at your own risk. We do not automatically extend the period of reservation if a student wants to stay longer. </p>
    <p class="minpar">After inspection of the room, {$insName} can refund the deposit by bank transfer on the following account :
  </p>
  <div class="TRdiv"><span style="display: block;
position:absolute;
width: 130px;
text-align: right;" >Account Holder Name:</span><span style="padding-left: 150px;
    font-size:small;
    font-size: 15px;">{$acName}</span></div>
  <div class="TRdiv"><span style="display: block;
position:absolute;
width: 130px;
text-align: right;">IBAN :</span><span style="padding-left: 150px;
    font-size:small;
    font-size: 15px;position:absolute;">{$stiban}</span></div>
  <div class="TRdiv" ><span style="display: block;
position:absolute;
width: 130px;
text-align: right;">BIC :</span><span style="padding-left: 150px;
    font-size:small;
    font-size: 15px;">{$stbic}</span></div>


    
</div>
    <div style="width:425px;position: relative;float: right;">
        <p style="padding-top: 15px;
padding-left: 10px;
font-size: medium;
">Please select the type of room you prefer :</p>
        <div style="width: 350px;padding-top: 15px;">{iteration:iResidence}{$resid}{/iteration:iResidence}</div>
    </div>
        </div>
</form>
    <div>&nbsp;</div>
</div>
        