<h2>Learning agreement</h2>

<form action="" method="post" enctype="multipart/form-data" id="changagreement">
<fieldset>
        <legend>Student</legend>

        <p>
            <span>Name of student: </span>
            {$nameStudent}
        </p>

        <p>
            <span>Sending institution: </span>
            {$sendingInstitution}
        </p>

        <p>
            <span>Country: </span>
            {$countrySendingInstitution}
        </p>

    </fieldset>

    <h3>Changes to original proposed study programme/learning agreement.</h3>
    <p>(To be filled in <strong>only</strong> if appropriate)</p>

    <fieldset>
        <table id="coursesTable">
             <tr>
                            <td></td><td></td><td></td>
                            <td><input type="button" name="addCourse" value="Add" id="addCourse"/><input type="button" name="remCourse" value="Remove" id="remCourse" /><input type="text" id="courseCount" name="courseCount" value="{$courseCount}" /></td>
                        </tr>
            <tr>
                <th>Course unit code (if any) and page no. of the information package</th>
                <th>Course unit title (as indicated in the information package)</th>
                <th> Deleted course unit - Added course unit</th>
                <th>Number of ECTS credits</th>
            </tr>
            {iteration:iCoursesOrNot}
            {$row}
            {/iteration:iCoursesOrNot}
        </table>
    </fieldset>

    <fieldset>


        <div class="TRdiv">
            <label for="sign"><span>Student's signature: </span></label>
            <input class="validate[required] text-input" type="text" id="sign" name="sign" value="{$sign|htmlentities}" />
            <span class="req">*</span>
        </div>

        <div class="TRdiv">
            <label for="signDate"><span>Date: </span></label>
            <input class="validate[required,custom[date]] text-input" type="text" id="signDate" name="signDate" value="{$signDate|htmlentities}" />
            <span class="req">*</span>
        </div>


    </fieldset>


    <fieldset>
        <legend>Sending Institution</legend>
        <p>We confirm that this proposed programme of study/learning agreement is approved.</p>

        <div class="TRdiv">
            <label for="signDepSign"><span>Departamental coordinator's signature</span></label>
            <input class="validate[required] text-input" type="text" id="signDepSign" name="signDepSign" value="{$signDepSign|htmlentities}" />
            <span class="req">*</span>
        </div>

        <div class="TRdiv">
            <label for="signDepSignDate"><span>Date: </span></label>
            <input class="validate[required,custom[date]] text-input" type="text" id="signDepSignDate" name="signDepSignDate" value="{$signDepSignDate|htmlentities}" />
            <span class="req">*</span>
        </div>

        <div class="TRdiv">
            <label for="signInstSign"><span>Institutional coordinator's signature</span></label>
            <input class="validate[required] text-input" type="text" id="signInstSign" name="signInstSign" value="{$signInstSign|htmlentities}" />
            <span class="req">*</span>
        </div>

        <div class="TRdiv">
            <label for="signInstSignDate"><span>Date: </span></label>
            <input class="validate[required,custom[date]] text-input" type="text" id="signInstSignDate" name="signInstSignDate" value="{$signInstSignDate|htmlentities}" />
            <span class="req">*</span>
        </div>

    </fieldset>

    <fieldset>
        <legend>Receiving Institution</legend>
        
        <p>We confirm that this proposed programme of study/learning agreement is approved.</p>

        <div class="TRdiv">
            <label for="signDepSign2"><span>Departamental coordinator's signature</span></label>
            <input class="validate[required] text-input" type="text" id="signDepSign2" name="signDepSign2" value="{$signDepSign2|htmlentities}" />
            <span class="req">*</span>
        </div>

        <div class="TRdiv">
            <label for="signDepSignDate2"><span>Date: </span></label>
            <input class="validate[required,custom[date]] text-input" type="text" id="signDepSignDate2" name="signDepSignDate2" value="{$signDepSignDate2|htmlentities}" />
            <span class="req">*</span>
        </div>

        <div class="TRdiv">
            <label for="signInstSign2"><span>Institutional coordinator's signature</span></label>
            <input class="validate[required] text-input" type="text" id="signInstSign2" name="signInstSign2" value="{$signInstSign2|htmlentities}" />
            <span class="req">*</span>
        </div>

        <div class="TRdiv">
            <label for="signInstSignDate2"><span>Date: </span></label>
            <input class="validate[required,custom[date]] text-input" type="text" id="signInstSignDate2" name="signInstSignDate2" value="{$signInstSignDate2|htmlentities}" />
            <span class="req">*</span>
        </div>

    </fieldset>

    
</form>