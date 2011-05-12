<style type="text/css">
.mainDiv{
    
}

.spright {
   

}
.spleft {
    
   
}
.minHead {



}
</style>
</head>

<div style="font-family: Arial,  Verdana, Helvetica, sans-serif;font-size:12px;border:solid 2px #b7ddf2;background:#ebf4fb;width:850px;margin-left: 20%;">
    <h1 align="center">Learning Agreement Change</h1>
    <form id="form1" name="form1" method="post" action="">
    <div class="XDiv">

        <div ><p style="padding-top: 15px;padding-left: 10px;font-size: medium;">Student Information</p></div>

        <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Students Name :</span><span style=" padding-left: 150px;font-size:small;font-size:15px;">{$studentsName}</span></div>
        <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Sending Institution :</span><span style=" padding-left: 150px;font-size:small;font-size:15px;">{$instName}</span></div>
        <div class="TRdiv"><span style="display: block;position:absolute;width: 120px;text-align: right;">Country :</span><span style=" padding-left: 150px;font-size:small;font-size:15px;">{$country}</span></div>
             <div><h2 style="padding-top: 15px;padding-left: 10px;font-size: medium;">Changes to Original Learning Agreement</h2></div>


<div class="TRDiv" align="center">


            <table >
                <thead>
            
                    <tr>
                        <th>Course Code</th>
                        <th>Course Unit Title<br/>(as indicated in the course catalogue)</th>
                        <th width="100px">Number Of<br/>ECTS Credits</th>
                        <th width="50px">Action</th>
                       

                    </tr>
                </thead>
                <tbody>
                    {iteration:iStudentCoursesChange}{$studentCoursesChange}{/iteration:iStudentCoursesChange}
                </tbody>
            </table>
        </div>




    </div>





</form>

</div>
