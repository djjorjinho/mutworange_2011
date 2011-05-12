
{$errorString}

{option:showSelectAboardUser}
{$selectError}
<div class="buttonPanel">
<div class="small"><form method="POST" action=''>
    <input type="hidden" name="formAction" id="formValidate" value="doViewsended" />
    <input class="buttonLook" name="postForm" id="postForm" type="submit" value="{$view}"/>
    </form></div>
    </div>
<div class="selDiv">
    <h2>Welcome</h2>
    <p>Select Student from the list</p>

    <form method="post">Find:<select name="selection">
            <option value="userId">Matr. Num</option>
            <option value="familyName">Last Name</option>

        </select><input type="hidden" name="pos"  value="{$view}"/><input type="text" name="Search" id="Search" value="" /><input type="hidden" name="formAction" id="formValidate" value="doSearch" /><input class="button" name="postForm" id="postForm" type="submit" value="Search"/></form>



    <table id="tblSearch"  class="sortable" width="450px" align="center">
        <tr>
            <th>MatrNum</th>
            <th>Name</th>
            <th>Last Name</th>
            <th></th>
        </tr>

        {iteration:iStudentsList}{$studentsList}{/iteration:iStudentsList}
    </table>
    <p>&nbsp;</p>
    <div class="leftAlDiv" align="right">
        <form method="post">
            <input type="hidden" name="prev" id="prev" value="{$prev}" />
            <input type="hidden" name="pos"  value="{$view}"/>

            <input type="hidden" name="formAction" id="formValidate" value="doPrev" />
            <input {$hiddenP} class="button" name="postForm" id="postForm" type="submit" value="Previous"/>
        </form>
    </div>

    <div class="rightAlDiv" align="left">

        <form  method="post">
            <input type="hidden" name="pos"   value="{$view}"/>
            <input type="hidden" name="next" id="next" value="{$next}" />

            <input type="hidden" name="formAction" id="formValidate" value="doNext" />
            <input {$hiddenN}  class="button" name="postForm" id="postForm" type="submit" value="Next"/>
        </form>
    </div>
    <p>&nbsp;</p>



</div>

{/option:showSelectAboardUser}

{option:showCertificates}
{option:showCertS}
<div class="buttonPanel">
<div class="small">
    <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="User" id="Usera" value="{$seluser}" />
    <input type="hidden" name="formAction" id="formValidate" value="doSelectaction" />
    <input class="buttonLook" name="postForm" id="postForm" type="submit" value="Certificate of Arrival"/>
</form></div>
    <div class="small">
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="User" id="Usera" value="{$seluser}" />
    <input type="hidden" name="formAction" id="formValidate" value="doSelectaction" />
    <input class="buttonLook" name="postForm" id="postForm" type="submit" value="Certificate of Departure"/>
  
</form></div>
       

    </div>
{/option:showCertS}   


<div class="backbtn"> <a href='{$back}' title="ErasmusLine">back</a></div>
<div>
<form id="form1" action="" method="post" enctype="multipart/form-data" >
    <div class="leftAlDiv">
        <p class="minHead">Student Information</p>
        <div class="TRdiv"><span class="spleft">First Name :</span><span class="spright">{$stFirstName}</span></div>
        <div class="TRdiv"><span class="spleft">Last Name :</span><span class="spright">{$stLastName}</span></div>
        <div class="TRdiv"><span class="spleft">Gender :</span><span class="spright">{$stGender}</span></div>


        <div><p class="minHead">Sending Institution Information</p></div>
        <div class="TRdiv"><span class="spleft">Institution Name :</span><span class="spright">{$seInName}</span></div>
        <div class="TRdiv"><span class="spleft">Department :</span><span class="spright">{$seCorDep}</span></div>
        <div><p class="minHead">Departmental Coordinator Information</p></div>
        <div class="TRdiv"><span class="spleft">Name :</span><span class="spright">{$seCorName}</span></div>
        <div class="TRdiv"><span class="spleft">E-mail :</span><span class="spright">{$seCorMail}</span></div>
        <div class="TRdiv"><span class="spleft">Tel :</span><span class="spright">{$seCorTel}</span></div>
        <div class="TRdiv"><span class="spleft">Fax :</span><span class="spright">{$seCorFax}</span></div>


    </div>
    <div class="rightAlDiv">
        <p class="minHead">&nbsp;</p>
        <div class="TRdiv"><span class="spleft">Date of Birth :</span><span class="spright">{$stDtBirh}</span></div>
        <div class="TRdiv"><span class="spleft">Place of Birth :</span><span class="spright">{$stPlBirh}</span></div>
        <div class="TRdiv"><span class="spleft">E-mail :</span><span class="spright">{$stMail}</span></div>



        <div class="TRdiv"><p class="minHead">Receiving Institution Information</p></div>
        <div class="TRdiv"><span class="spleft">Institution Name :</span><span class="spright">{$reInName}</span></div>
        <div class="TRdiv"><span class="spleft">Department :</span><span class="spright">{$reCorDep}</span></div>
        <div class="TRdiv"><p class="minHead">Departmental Coordinator Information</p></div>
        <div class="TRdiv"><span class="spleft">Name :</span><span class="spright">{$reCorName}</span></div>
        <div class="TRdiv"><span class="spleft">E-mail :</span><span class="spright">{$reCorMail}</span></div>
        <div class="TRdiv"><span class="spleft">Tel :</span><span class="spright">{$reCorTel}</span></div>
        <div class="TRdiv"><span class="spleft">Fax :</span><span class="spright">{$reCorFax}</span></div>


    </div>

    <div class="alCenterDiv">

        <div><p class="minHead">{$form}</p></div>
        {$field}       


        {option:showSubmit}<div><p><input type="hidden" name="User" id="Usera" value="{$seluser}" />
                <input type="hidden" name="formAction" id="formValidate" value="doSubmit" />
                <input class="button" name="postForm" id="postForm" type="submit" value="Submit Form"/></p></div>{/option:showSubmit}
           
                {option:showResend}<div><p><input type="hidden" name="User" id="Usera" value="{$seluser}" />
                <input type="hidden" name="formAction" id="formValidate" value="doResend" />
                <input class="button" name="postForm" id="postForm" type="submit" value="Resend Form"/></p></div>{/option:showResend}
                {option:showResenddep}<div><p><input type="hidden" name="User" id="Usera" value="{$seluser}" />
                <input type="hidden" name="formAction" id="formValidate" value="doResenddep" />
                <input class="button" name="postForm" id="postForm" type="submit" value="Resend Form"/></p></div>{/option:showResenddep}
    </div>

</form>



{/option:showCertificates}
</div>