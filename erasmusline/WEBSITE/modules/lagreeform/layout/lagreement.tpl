<script type="text/javascript" src="./core/js/jquery/jquery.MultiFile.js"></script>
<div class="mainDiv">
    <h2>Learning agreement</h2>
    
    {option:oDenied}
    <div id="denied">
        <p><strong>The Learning Agreement has been denied.</strong></p>
        <p><strong>Motivation Home: </strong>{$motivationHome}</p>
        <p><strong>Motivation Host: </strong>{$motivationHost}</p>
        <p>Returned Learning Agreement{$returndAgree}</p>
    </div>
    {/option:oDenied}
    
    {option:oApproved}
    <div id="approved">
        <p><strong>The Learning Agreement has been approved.</strong></p>
        <p><strong>Motivation Home: </strong>{$motivationHome}</p>
        <p><strong>Motivation Host: </strong>{$motivationHost}</p>
        <p>Returned Learning Agreement:{$returndAgree}</p>
    </div>
    {/option:oApproved}
    
    {option:oPending}
    <div id="pending">
        <p><strong>The Learning Agreement is pending.</strong></p>
    </div>
    {/option:oPending}

    <form action="" method="post" enctype="multipart/form-data" id="lagreement" name="lagreement">
        <p><span class="req">{$error}</span></p>
        <fieldset>
            <legend>General info</legend>
            <p>
                <span>Academic Year: </span>
                {$acaYear}
            </p>

            <p>
                <span>Field of Study: </span>
                {$study}
            </p>

        </fieldset>
        <fieldset>
            <legend>Student - Personal info</legend>
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
            <p>
                <span>ECTS credits:</span> {$credits}
            </p>
        </fieldset>

        <fieldset>
            <legend>Receiving institution - General info</legend>
            <p>
                <span>Receiving Institution: </span>
                {$receivingInstitution}
            </p>

            <p>
                <span>Country: </span>
                {$countryReceivingInstitution}
            </p>

        </fieldset>

        <fieldset>
            <legend>Student - Courses in host institute</legend>
            <p><strong>Details of the proposed study programme abroad/learning agreement.</strong></p>
            
            <table id="coursesTable">
                <caption>Start typing the course unit title.</caption>
                <tr>
                    <th>Course unit code</th>
                    <th>Course unit title</th>
                    <th>ECTS credits</th>
                    <th>
                    <input type="button" name="addCourse" value="Add" id="addCourse"/><input type="button" name="remCourse" value="Remove" id="remCourse" /><input type="hidden" id="courseCount" name="courseCount" value="{$courseCount}" />
                </th>
                </tr>
                {option:oNotFilled}
                
                {/option:oNotFilled}
                {iteration:iCourses}
                {$row}
                {/iteration:iCourses}


            </table>

            <div class="TRdiv">
                <label for="sign"><span>Student's signature: </span></label>
            </div>

            <div class="TRdiv">
                <label for="signDate"><span>Date: </span></label>
                <input class="validate[required,custom[date]] text-input" type="text" id="signDate" name="signDate" />
            </div>


        </fieldset>

        {option:oNotFilled}
        <fieldset>
            <legend>Student - Submit Learning Agreement</legend>
            <p>
                <input type="hidden" name="formAction" id="formRegister" value="doAgree" />
                <input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
            </p>
        </fieldset>
        {/option:oNotFilled}

        {option:oCoor}

        <fieldset>
            <legend>Sending Institution - Confirmation</legend>
            <p><strong>We confirm that this proposed programme of study/learning agreement is approved.</strong></p>

            
             <div class="radioResidences">
                <div class="radioResidence">
                <label for="acceptedHome">The Learning Agreement is: </label>
                Accepted<input class="validate[required] radio" type="radio" name="acceptedHome" value="1" id="1" />
               Not accepted<input type="radio" class="validate[required] radio" name="acceptedHome" value="0" id="0"  />
            </div></div>
            
            <div class="TRdiv">
                <label for="signDepSignSend"><span>Departamental coordinator's signature</span></label>
            </div>


            <div class="TRdiv">
                <label for="signDepSignDateSend"><span>Date: </span></label>
                <input class="validate[required,custom[date]] text-input" type="text" id="signDepSignDateSend" name="signDepSignDateSend"  />
            </div>

            <div class="TRdiv">
                <label for="signInstSignSend"><span>Institutional coordinator's signature</span></label>

            </div>


            <div class="TRdiv">
                <label for="signInstSignDateSend"><span>Date: </span></label>
                <input class="validate[required,custom[date]] text-input" type="text" id="signInstSignDateSend" name="signInstSignDateSend"  />
            </div>

        </fieldset>

        <fieldset>
            <legend>Receiving Institution - Confirmation</legend>
            <p><strong>We confirm that this proposed programme of study/learning agreement is approved.</strong></p>

             <div class="radioResidences">
                <div class="radioResidence">
                <label for="acceptedHost">The Learning Agreement is: </label>
                Accepted<input class="validate[required] radio" type="radio" name="acceptedHost" value="1" id="1" />
                Not accepted<input type="radio" class="validate[required] radio" name="acceptedHost" value="0" id="0"  />
            </div></div>
            <div class="TRdiv">
                <label for="signDepSignRec"><span>Departamental coordinator's signature</span></label>
            </div>


            <div class="TRdiv">
                <label for="signDepSignDateRec"><span>Date: </span></label>
                <input class="validate[required,custom[date]] text-input" type="text" id="signDepSignDateRec" name="signDepSignDateRec" />
            </div>

            <div class="TRdiv">
                <label for="signInstSignRec"><span>Institutional coordinator's signature</span></label>

            </div>

            <div class="TRdiv">
                <label for="signInstSignDateRec"><span>Date: </span></label>
                <input class="validate[required,custom[date]] text-input" type="text" id="signInstSignDateRec" name="signInstSignDateRec"  />
            </div>
            
            <script language="javascript">
             function printpage()
              {
               window.print();
              }
            </script>
            <div class="TRdiv">
                <label for="print">Print this form</label>
                <input type="button" value="Print" onclick="printpage();" />
            </div>

        </fieldset>

        {/option:oCoor}

        {option:oOffice}
        <fieldset>
            <legend>Sending Institution - Send to host (if approved)</legend>
            <div class="TRdiv">
                <label for="coordinator">Motivation</label>
                <textarea class="validate[required],custom[textarea]" type="text" name="coordinator" id="coordinator" cols="50" rows="6"></textarea>
            </div>
            <div class="TRdiv">
                <label for="printed"><span>Attach signed Learning Agreement</span></label>
                <input type="file" class="multi" maxlength="1" accept="pdf" id="signImg" name="pic[]" /><span id="errRegPicture"></span>
            </div>
            <div class="TRdiv">
                <input type="hidden" name="formAction" id="formRegister" value="doTohostagree" />
                <input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
            </div>
        </fieldset>
        {/option:oOffice}

        {option:oHost}


        <fieldset>
            <legend>Receiving institution - Send to home institute</legend>
            <div class="TRdiv">
                <label for="coordinator">Motivation</label>
                <textarea class="validate[required],custom[textarea]" type="text" name="coordinator" id="coordinator" cols="50" rows="6"></textarea>
            </div>
            <div class="TRdiv">
                <label for="printed"><span>Attach signed Learning Agreement</span></label>
                <input type="file" class="multi" maxlength="1" accept="pdf" id="signImg" name="pic[]" /><span id="errRegPicture"></span>
            </div>
            <div class="TRdiv">
                <input type="hidden" name="formAction" id="formRegister" value="doMotivateagree" />
                <input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
            </div>
        </fieldset>
        {/option:oHost}


    </form>
</div>