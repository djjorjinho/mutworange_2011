{$errorString}

<form id="form1" name="form1" method="post" action="">
    <div class="XDiv">

        <div class="minHead"><p>Student Information</p></div>

        <div class="TRdiv"><span class="spleft">Students Name :</span><span class="spright">{$studentsName}</span></div>
        <div class="TRdiv"><span class="spleft">Sending Institution :</span><span class="spright">{$instName}</span></div>
        <div class="TRdiv"><span class="spleft">Country :</span><span class="spright">{$country}</span></div>


        <div class="minHead" align="center"><p>Changes to Original Learning Agreement</p></div>

        <h3>ECTS Credits Remaining: {$ECTS}</h3>
        <div class="TRDiv" align="center">


            <table id="LearnAgrChange">
                <thead>

                    <tr>
                        <th>Course Unit Code</th>
                        <th>Course Unit Title<br/>(as indicated in the course catalogue)<br/></th>
                        <th width="100px">Number Of<br/>ECTS Credits</th>
                        <th width="50px">Status</th>

                    </tr>
                </thead>
                <tbody>

                    {iteration:iStudentCourses}{$studentCourses}{/iteration:iStudentCourses}

                </tbody>
            </table>
        </div>




    </div>



</form>





