<div class="mainDiv">
{$errorString}
{option:showSelectTranscriptSelect}
{$selectError}
<div class="buttonPanel">
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

    </div>
    <div style="width:450px;padding-left: 20%;margin-top: 2%;">
        <table id="tblSearch"  class="sortable" width="450px" align="center">
            <thead>
                <tr>
                    <th>MatrNum</th>
                    <th>Name</th>
                    <th>Last Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                {iteration:iStudentsList}{$studentsList}{/iteration:iStudentsList}
            </tbody>
        </table>
    </div>
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
{/option:showSelectTranscriptSelect}


{option:showTranscript}
<p align="right"><a href='{$back}'>back</a></p>
<form id="form1" action="" method="post" enctype="multipart/form-data" >


    <div class="leftAlDiv">
        <p class="minHead">Student Information</p>
        <div ><span class="spleft">First Name :</span><span class="spright" id="lol" name="lol">{$stFirstName}</span></div>
        <div ><span class="spleft">Last Name :</span><span class="spright">{$stLastName}</span></div>
        <div ><span class="spleft">Gender :</span><span class="spright">{$stGender}</span></div>
        <div ><span class="spleft">Date of Birth :</span><span class="spright">{$stDtBirh}</span></div>

        <div><p class="minHead">Sending Institution Information</p></div>
        <div><span class="spleft">Institution Name :</span><span class="spright">{$seInName}</span></div>
        <div><p class="minHead">Departmental Coordinator Information</p></div>
        <div><span class="spleft">Name :</span><span class="spright">{$seCorName}</span></div>
        <div><span class="spleft">E-mail :</span><span class="spright">{$seCorMail}</span></div>
        <div><span class="spleft">Tel :</span><span class="spright">{$seCorTel}</span></div>
        <div><span class="spleft">Fax :</span><span class="spright">{$seCorFax}</span></div>
    </div>
    <div class="rightAlDiv">
        <p class="minHead">&nbsp;</p>
        <div ><span class="spleft">Place of Birth :</span><span class="spright">{$stPlBirh}</span></div>
        <div ><span class="spleft">Matriculation Date :</span><span class="spright">{$stMatrDate}</span></div>
        <div ><span class="spleft">Matriculation Num :</span><span class="spright">{$stMatrNum}</span></div>
        <div ><span class="spleft">E-mail :</span><span class="spright">{$stMail}</span></div>

        <div ><p class="minHead">Receiving Institution Information</p></div>
        <div ><span class="spleft">Institution Name :</span><span class="spright">{$reInName}</span></div>
        <div ><p class="minHead">Departmental Coordinator Information</p></div>
        <div ><span class="spleft">Name :</span><span class="spright">{$reCorName}</span></div>
        <div ><span class="spleft">E-mail :</span><span class="spright">{$reCorMail}</span></div>
        <div ><span class="spleft">Tel :</span><span class="spright">{$reCorTel}</span></div>
        <div ><span class="spleft">Fax :</span><span class="spright">{$reCorFax}</span></div>
    </div>

    <div class="alCenterDiv">
        <p class="minHead">Grades</p>
        <div style="padding-left: 5%;padding-top: 3%;text-align: center;vertical-align: middle;">
            <table id="lol">
                <thead>

                    <tr align="center">
                        <th class="centerTableHeader">Course<br/>Code</th>
                        <th class="centerTableHeader">Course<br/>Title</th>
                        <th class="centerTableHeader">ECTS<br/>Credits</th>

                        <th class="centerTableHeader">Duration<br/>of the Course</th>
                        <th class="centerTableHeader">Local<br/>Grade</th>
                        <th class="centerTableHeader">ECTS<br/>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    {option:showSendAtr}
                    
                        {iteration:iCourses}{$courses}{/iteration:iCourses}
                    
                    {/option:showSendAtr}
                    {option:showSendedTr}
                    {iteration:iStudentRec}{$studentRec}{/iteration:iStudentRec}
                    {/option:showSendedTr}
                </tbody>
            </table>
        </div>

        <p class="minHead">Information</p>
        <div class="tblx" align="center">
            <p>Duration of the course unit: Y = 1 full academic year, 1S = 1 semester, 1T = 1 term/trimester,<br/> 2S = 2 semesters, 2T = 2 terms/trimesters.</p>
            <p>Description of the institutional grading system: The result achieved in a subject, whether through continuous assessment or in an examination, is generally expressed in a 0 to 20 grading scheme. The lowest passing grade is 10. </p>
            <br/>

            <table  border="0">
                <tr >
                    <th align="center">ECTS Grade </th>
                    <th style="text-align: center;">Definition</th>
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
                    <th style="width:250px;text-align:center;" colspan="2">ECTS Credits </th>
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
        <p><input type="hidden" name="num" value="{$num}" /><input type="hidden" name="form" value="{$form}" />
            <input type="hidden" name="formAction" id="formValidate" value="{$action}" />
            <input class="button" name="postForm" id="postForm" type="submit" value="Submit Form"/></p>
    </div>

</form>
{/option:showTranscript}
</div>