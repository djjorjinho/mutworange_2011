{$errorString}
{option:showSelectTranscriptSelect}
{$selectError}
<div class="selDiv">
    <div class="small"><form method="POST" action=''>
    <input type="hidden" name="formAction" id="formValidate" value="doViewsended" />
    <input class="buttonLook" name="postForm" id="postForm" type="submit" value="{$view}"/>
    </form></div>
  
</div>
<div class="alCenterDiv">
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
    
    
    
</div>
{/option:showSelectTranscriptSelect}


{option:showTranscript}

<p align="right"><a href='{$back}'>back</a></p>
<form id="form1" action="" method="post" enctype="multipart/form-data" >


    <div class="leftAlDiv">
        <p class="minHead">Student Information</p>
        <div class="TRdiv"><span class="spleft">First Name :</span><span class="spright" id="lol" name="lol">{$stFirstName}</span></div>
        <div class="TRdiv"><span class="spleft">Last Name :</span><span class="spright">{$stLastName}</span></div>
        <div class="TRdiv"><span class="spleft">Gender :</span><span class="spright">{$stGender}</span></div>
        <div class="TRdiv"><span class="spleft">Date of Birth :</span><span class="spright">{$stDtBirh}</span></div>

        <div><p class="minHead">Sending Institution Information</p></div>
        <div><span class="spleft">Institution Name :</span><span class="spright">{$seInName}</span></div>
        <div><span class="spleft">Department :</span><span class="spright">{$seCorDep}</span></div>
        <div><p class="minHead">Departmental Coordinator Information</p></div>
        <div><span class="spleft">Name :</span><span class="spright">{$seCorName}</span></div>
        <div><span class="spleft">E-mail :</span><span class="spright">{$seCorMail}</span></div>
        <div><span class="spleft">Tel :</span><span class="spright">{$seCorTel}</span></div>
        <div><span class="spleft">Fax :</span><span class="spright">{$seCorFax}</span></div>
    </div>
    <div class="rightAlDiv">
        <p class="minHead">&nbsp;</p>
        <div class="TRdiv"><span class="spleft">Place of Birth :</span><span class="spright">{$stPlBirh}</span></div>
        <div class="TRdiv"><span class="spleft">Matriculation Date :</span><span class="spright">{$stMatrDate}</span></div>
        <div class="TRdiv"><span class="spleft">Matriculation Number :</span><span class="spright">{$stMatrNum}</span></div>
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
        <p class="minHead">Grades</p>

        <div class="tblx" >
            <table  id="lol" align="center">
                <thead>
               {option:showSendAtrBtn}
                    <tr>
                        
                        <td></td><td></td><td></td><td></td>
                        <td colspan="5"><input type="button" name="Action" value="Add" id="add"/><input type="button" name="Action" value="Remove" id="rem" /></td>
                    </tr>
                    {/option:showSendAtrBtn}
                    <tr><td colspan="5">&nbsp;</td></tr>
                    <tr>
                        <th>Course Title</th>
                        <th>ECTS<br/>Credits</th>

                        <th>Duration<br/>of the Course</th>
                        <th>Local<br/>Grade</th>
                        <th>ECTS<br/>Grade</th>
                    </tr>
                </thead>
                <tbody>
{option:showSendAtr}
                    <tr>
                        <td><select class="validate[required]" name="coursetitle1" id="1" onChange="onSelectChange(1);"><option value=""></option>{iteration:iCourses}{$courses}{/iteration:iCourses}</select> </td>
                        <td align="center"><div id="ec1">-</div></td>
                        <td align="center"><select  name="corDur1" id="corDur1" ><option></option><option>Y</option><option>1S</option><option>1T</option><option>2S</option><option>2T</option></select></td>
                        <td><input class="validate[required,custom[integer]]" type="text" size="5" maxlength="2" name="locGrade1" id="locGrade1" /></td>
                        <td align="center"><select  name="ecGrade1" id="ecGrade1" class="validate[required]"><option></option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>FX</option></select></td>
                    </tr>
{/option:showSendAtr}
{option:showSendedTr}
     {iteration:iStudentRec}{$studentRec}{/iteration:iStudentRec}
{/option:showSendedTr}

                </tbody>


            </table>
            <div id="output"></div>
        </div>

        <p class="minHead">Information</p>
        <div class="tblx" align="center">
            <p>Duration of the course unit: Y = 1 full academic year, 1S = 1 semester, 1T = 1 term/trimester, 2S = 2 semesters, 2T = 2 terms/trimesters.</p>
            <p>Description of the institutional grading system: The result achieved in a subject, whether through continuous assessment or in an examination, is generally expressed in a 0 to 20 grading scheme. The lowest passing grade is 10. </p>
            <br/>

            <table border="0">
                <tr>
                    <th>ECTS Grade </th>
                    <th>Definition</th>
                </tr>
                <tr>
                    <td align="center">A</td>
                    <td>EXCELLENT - outstanding performance with only minor errors</td>
                </tr>
                <tr>
                    <td align="center">B</td>
                    <td>VERY GOOD - above the average standard but with some errors</td>
                </tr>
                <tr>
                    <td align="center">C</td>
                    <td>GOOD - generally sound work with a number of notable errors</td>
                </tr>
                <tr>
                    <td align="center">D</td>
                    <td>SATISFACTORY - fair but with significant shortcomings</td>
                </tr>
                <tr>
                    <td align="center">E</td>
                    <td>SUFFICIENT - performance meets the minimum criteria</td>
                </tr>
                <tr>
                    <td align="center">FX</td>
                    <td>FAIL - some work required before the credit can be awarded</td>
                </tr>
                <tr align="center">
                    <td>F</td>
                    <td align="left">FAIL - considerable further work is required</td>
                </tr>
            </table>
            <br/>

            <table border="0">
                <tr>
                    <th colspan="2">ECTS Credits </th>
                </tr>
                <tr>
                    <td>1 full academic year</td>
                    <td>60 credits</td>
                </tr>
                <tr>
                    <td>1 semester</td>
                    <td>30 credits</td>
                </tr>
                <tr>
                    <td>1 term/trimister</td>
                    <td>20 credits</td>
                </tr>
            </table>
        </div>
        <br/><br/>
    </div>
    <p>&nbsp;</p>
    <div align="center">
        <p><input type="hidden" name="num" value="{$num}" />
            <input type="hidden" name="formAction" id="formValidate" value="{$action}" />
            <input class="button" name="postForm" id="postForm" type="submit" value="Submit Form"/></p>
    </div>

</form>
{/option:showTranscript}