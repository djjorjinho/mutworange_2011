<div class="mainDiv">
{option:student}
    {$errorString}

<form id="learnCH" name="learnCH" method="post" action="">
    
    <div class="XDiv">
{option:oDenied}
    <div class="errorPHP">
        <p><strong>Previous Learning Agreement Changes has been denied.</strong></p>
    </div>
    {/option:oDenied}
    
    {option:oApproved}
    <div class="SuccessPHP">
        <p><strong>Previous Learning Agreement Changes Form has been approved.</strong></p>
    </div>
    {/option:oApproved}
    
    {option:oPending}
    <div class="SuccessPHP">
        <p><strong>Learning Agreement Changes is pending.</strong></p>
    </div>
    {/option:oPending}
        <div class="minHead"><p>Student Information</p></div>

        <div ><span class="spleft">Students Name :</span><span class="spright">{$studentsName}</span></div>
        <div ><span class="spleft">Sending Institution :</span><span class="spright">{$instName}</span></div>
        <div ><span class="spleft">Country :</span><span class="spright">{$country}</span></div>


        <div class="minHead" align="center"><p>Changes to Original Learning Agreement</p></div>

        <h3>ECTS Credits Remaining: <span id="points">{$ECTS}</span> / {$ECTStot}</h3>
        <div  align="center">


            <table id="LearnAgrChange">
                <thead>

                    <tr>
                        <th>Course Unit Code</th>
                        <th>Course Unit Title<br/>(as indicated in the course catalogue)<br/></th>
                        <th width="100px">Number Of<br/>ECTS Credits</th>
                        <th width="50px">Status</th>

                    </tr>
                </thead>
                <tbody align="center">

                    {iteration:iStudentCourses}{$studentCourses}{/iteration:iStudentCourses}

                </tbody>
            </table>
        </div>

        <div class="alCenterDiv" style="padding-top: 30px;">
                    <input type="hidden" name="formAction" id="formValidate" value="doSubmit" />
                    <input class="button" name="postForm" id="postForm" type="submit" value="Submit"/></p></div>
       

    </div>
</form>
{/option:student}

{option:act1}
    <div class="alCenterDiv">
        <h2>Welcome</h2>
        <p>Select Student from the list</p>

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
 
{/option:act1}
{option:act2}
{$errorString}

<form id="learnCH" name="learnCH" method="post" action="">
    <div class="XDiv">
    {option:oPending}
    <div class="SuccessPHP">
        <p><strong>Learning Agreement Changes is pending.</strong></p>
    </div>
    {/option:oPending}
        <div class="minHead"><p>Student Information</p></div>

        <div ><span class="spleft">Students Name :</span><span class="spright">{$studentsName}</span></div>
        <div ><span class="spleft">Sending Institution :</span><span class="spright">{$instName}</span></div>
        <div ><span class="spleft">Country :</span><span class="spright">{$country}</span></div>


        <div class="minHead" align="center"><p>Changes to Original Learning Agreement</p></div>

        <h3>ECTS Credits Remaining: <span id="points">{$ECTS}</span> / {$ECTStot}</h3>
        <div  align="center">


            <table id="LearnAgrChange">
                <thead>

                    <tr>
                        <th>Course Unit Code</th>
                        <th align="center">Course Unit Title<br/>(as indicated in the course catalogue)<br/></th>
                        <th width="100px">Number Of<br/>ECTS Credits</th>

                    </tr>
                </thead>
                <tbody align="center">

                    {iteration:iSCourses}{$sCourses}{/iteration:iSCourses}

                </tbody>
            </table>
        </div>
        {$button}
       

    </div>
</form>
{/option:act2}


</div>
