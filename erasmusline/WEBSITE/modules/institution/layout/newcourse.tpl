

<div class="mainDiv">
<h3>Admin panel</h3>
<p>Here you can add a new course</p>
<form action="" method="post" enctype="multipart/form-data" id="addCourse">	
<fieldset>
    <legend><h3>New course form</h3></legend>     
    <span class="req" id="error">{$error}</span><br />
	<div class="TRdiv">
            <label for="coursecode"><span>Course Code : </span></label>
            <input class="validate[required,custom[onlyLetterSp]] text-input" 
            type="text" name="coursecode" id="coursecode" value="{$courseCode|htmlentities}"/>
            <span class="req" id="msgCourseCode">{$msgCourseCode|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="coursename"><span>Course Name : </span></label>
            <input class="validate[required,custom[onlyLetterSp]] text-input" 
            type="text" name="coursename" id="coursename" value="{$courseName|htmlentities}"/>
            <span class="req" id="msgCourseName">{$msgCourseName|htmlentities}</span>	
	</div>
        <div class="TRdiv">
            <label for="ects"><span>ECTs Credits : </span></label>
            <input class="validate[required,custom[onlyNumberSp]] text-input" 
            type="text" name="ects" id="ects" value="{$eCTs|htmlentities}"/>  
            <span class="req" id="msgEcts">{$msgECTs|htmlentities}</span>
	</div>
        <div class="TRdiv">
            <label for="coursedesc"><span>Course Description : </span></label>           
            <textarea class="validate[required],custom[textarea]" type="text" name="coursedesc" 
            id="coursedesc" cols="33" rows="3">{$courseDesc|htmlentities}</textarea>
            <span class="req" id="msgCourseDesc">{$msgCourseDesc|htmlentities}</span>	
	</div>

        <div class="TRdiv">
            <label for="education"><span>Education : </span></label>
            <select name="education" value="{$education|htmlentities}">
               {iteration:iEducations}
                     {$education}
               {/iteration:iEducations}
            </select>
            <span class="req" id="msgEducation">{$msgEducation|htmlentities}</span>
	</div>
        
 
</fieldset>
        <div class="TRdiv">
        <input type="hidden" name="option" id="option" value="newcourse"></input>              
		<input type="hidden" name="formAction" id="formAddCourse" value="doSubmit"></input>
		<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"></input>
	</div>    
</form>
</div>



<script type="text/javascript" src="./core/js/jquery/jquery-1.5.js"></script>
	<script type="text/javascript" src="./core/js/progressbar/js/jquery.progressbar.js"></script>
        <script type="text/javascript" src="./core/js/jquery/jquery.pstrength-min.1.2.js"></script>
        <script type="text/javascript" src="./core/js/jquery/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="./core/js/jquery/jquery.validationEngine-en.js"></script>
        <script type="text/javascript" src="./core/js/jquery/jquery.MultiFile.js"></script>
        <link rel="stylesheet" href="./core/css/validationEngine.jquery.css" type="text/css"></link>
        <script type="text/javascript">
           jQuery(document).ready(function(){
           // binds form submission and fields to the validation engine
           jQuery("#register").validationEngine();
       });
</script> 