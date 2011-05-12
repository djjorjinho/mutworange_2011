{$errorString}

<form id="form1" name="form1" method="post" action="">
    <div class="XDiv">

        <div class="minHead"><p>Student Information</p></div>

        <div class="TRdiv"><span class="spleft">Students Name :</span><span class="spright">{$studentsName}</span></div>
        <div class="TRdiv"><span class="spleft">Sending Institution :</span><span class="spright">{$instName}</span></div>
        <div class="TRdiv"><span class="spleft">Country :</span><span class="spright">{$country}</span></div>


        <div class="minHead" align="center"><p>Changes to Original Learning Agreement</p></div>


<div class="TRDiv" align="center">


            <table id="LearnAgrChange">
                <thead>
                    <tr>
                                          
                        <td colspan="6"  align="right"><input type="button" name="Action" value="Add" id="addCourseChange"/><input type="button" name="Action" value="Remove" id="remCourseChange" /></td>
                    </tr>
                    <tr>
                   
                        <th>Course Unit Title<br/>(as indicated in the course catalogue)<br/></th>
                        <th width="100px">Number Of<br/>ECTS Credits</th>
                        <th width="50px">Add</th>
                        <th width="50px">Remove</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td ><select class="validate[required]" name="coursetitle1" id="1" onChange="onSelectChange(1);" ><option value=""></option>{iteration:iStudentCourses}{$studentCourses}{/iteration:iStudentCourses}</select> </td>
                        <td><div id="ec1">-</div></td>
                        <td><input class="validate[required]" type="radio" name="rad1" value="Add" id="rad1" /></td>
                        <td><input class="validate[required]" type="radio" name="rad1" value="Remove" id="rad1" /></td>

                    </tr>
                </tbody>
            </table>
        </div>




    </div>

    <div align="center">
        <p><input type="hidden" name="num" value="{$num}" />
            <input type="hidden" name="formAction" id="formValidate" value="doSubmitchange" />
            <input class="button" name="postForm" id="postForm" type="submit" value="Submit Form"/></p>
    </div>



</form>





