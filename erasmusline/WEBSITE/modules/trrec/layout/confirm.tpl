<div style="font-family: Arial,  Verdana, Helvetica, sans-serif;font-size:12px;border:solid 2px #b7ddf2;background:#ebf4fb;width:850px;margin-left: 20%;">
    <h1 align="center"> TranScript Of Records</h1>
    <form id="form1" action="" method="post" enctype="multipart/form-data" >


        <div style="width:425px;position: relative;float: left;">
            <p style="padding-top: 15px;padding-left: 10px;font-size: medium;">Student Information</p>
            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">First Name :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$stFirstName|htmlentities}</span></div>
            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Last Name :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$stLastName}</span></div>
            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Gender :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$stGender}</span></div>
            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Place of Birth :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$stPlBirh}</span></div>

            <div><p style="padding-top: 15px;padding-left: 10px;font-size: medium;">Sending Institution Information</p></div>
            <div><span style="display: block;position:absolute;width: 120px;text-align: right;">Institution Name :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$seInName}</span></div>
            <div><p style="padding-top: 15px;padding-left: 10px;font-size: medium;">Departmental Coordinator Information</p></div>
            <div><span style="display: block;position:absolute;width: 120px;text-align: right;">Name :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$seCorName}</span></div>
            <div><span style="display: block;position:absolute;width: 120px;text-align: right;">E-mail :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$seCorMail}</span></div>
            <div><span style="display: block;position:absolute;width: 120px;text-align: right;">Tel :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$seCorTel}</span></div>
            <div><span style="display: block;position:absolute;width: 120px;text-align: right;">Fax :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$seCorFax}</span></div>

        </div>
        <div style="width:425px;position: relative;float: right;">

            <p style="padding-top: 15px;padding-left: 10px;font-size: medium;">&nbsp;</p>

            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Date of Birth :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$stDtBirh}</span></div>
            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Matriculation Date :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$stMatrDate}</span></div>
            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Matriculation Number :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$stMatrNum}</span></div>

            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">E-mail :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$stMail}</span></div>

            <div class="TRdiv"><p style="padding-top: 15px;padding-left: 10px;font-size: medium;">Receiving Institution Information</p></div>
            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Institution Name :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$reInName}</span></div>
            <div class="TRdiv"><p style="padding-top: 15px;padding-left: 10px;font-size: medium;">Departmental Coordinator Information</p></div>
            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Name :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$reCorName}</span></div>
            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">E-mail :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$reCorMail}</span></div>
            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Tel :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$reCorTel}</span></div>
            <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Fax :</span><span style="padding-left: 150px;font-size:small;font-size: 15px;">{$reCorFax}</span></div>
        </div>


        <div style="width:100%;position: relative;float: left;">
            <p style="padding-top: 15px;padding-left: 10px;font-size: medium;">Grades</p>

            <div align="center">
                <table>
                    <thead>

                        <tr>
                            <th>Course<br/>Unit Code </th>
                            <th>Course Unit Title</th>
                            <th>ECTS<br/>Credits</th>
                            <th>Duration<br/>of the Course</th>
                            <th>Local<br/>Grade</th>
                            <th>ECTS<br/>Grade</th>
                        </tr>
                    </thead>
                    <tbody align="center">



                        {iteration:iStudentRec}{$studentRec}{/iteration:iStudentRec}


                    </tbody>
                </table>
            </div>

            <p style="padding-top: 15px;padding-left: 10px;font-size: medium;">Information</p>
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


    </form>

</div>