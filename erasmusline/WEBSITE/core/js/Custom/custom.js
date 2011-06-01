     



function onSelectChange(i){
    
    var selected = $("#"+i+" option:selected");	
    var output = "-";
    if(selected.val() != ""){
        output = $("#"+i+" :selected").attr("name");
}
    $("#ec"+i).html(output);
}

            
            
jQuery(document).ready(function(){
    
    
    
    // binds form submission and fields to the validation engine
    jQuery("#form1").validationEngine();


//Add Row - Course Title To Transcript Of Records main.tpl
    $('#add').click(function(){
        var i = $('#lol >tbody >tr').length+1;
        
        var row = $('#1').clone(true);
        $('#lol > tbody:last').append('<tr><td><select class="validate[required]" id="'+i+'" onChange="onSelectChange('+i+');" name="coursetitle'+i+'">'+row.html()+'</select></td><td align="center"><div id="ec'+i+'">-</div></td><td align="center"><select name="corDur'+i+'" id="corDur'+i+'" ><option></option><option>Y</option><option>1S</option><option>1T</option><option>2S</option><option>2T</option></select></td><td><input class="validate[required,custom[integer]]" type="text" size="5" maxlength="2" name="locGrade'+i+'" id="locGrade'+i+'" /></td><td align="center"><select  name="ecGrade'+i+'" id="ecGrade'+i+'" class="validate[required]"><option></option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>FX</option></select></td></tr>');

    });
    
    $('#rem').click(function(){
        if ($('#lol tr').length > 4)
            $('#lol tr:last').remove();
    }
    ); 
        
    
    //Add Row - Learning AgreedMent Change tpl

        $('#addCourseChange').click(function(){
        var i = $('#LearnAgrChange >tbody >tr').length+1;
        
        var row = $('#1').clone(true);
        $('#LearnAgrChange > tbody:last').append('<tr><td align="center"><select class="validate[required]" id="'+i+'" onChange="onSelectChange('+i+');" name="coursetitle'+i+'">'+row.html()+'</select></td><td align="center"><div id="ec'+i+'">-</div></td><td align="center"><input class="validate[required]" type="radio" name="rad'+i+'" value="Add" id="rad'+i+'" /></td><td align="center"><input class="validate[required]" type="radio" name="rad'+i+'" value="Remove" id="rad'+i+'" /></td></tr>');

    });


    $('#remCourseChange').click(function(){
        if ($('#LearnAgrChange tr').length > 3)
            $('#LearnAgrChange tr:last').remove();
    }
    ); 
        




}); 
     
     
//     jQuery(document).ready(function(){
// $('.course').change(function(){
//        var i = $('#LearnAgrChange >tbody >tr option:checked').length;
//        alert($('#LearnAgrChange .ects'+i+'').text());
//        alert(i);
//
//    });        
//         
//     
//  }); 
  
  function getEcts(i){
      var selEcts=$('#ects'+i).text();
      var totalEcts=$('#points').text();
      var count=0;
    if ($('#course'+i).attr('checked')) {
         count=parseInt(totalEcts)-parseInt(selEcts);
        } 
        else {
              count = parseInt(selEcts)+parseInt(totalEcts);
}
    if (count<0){
        $('#course'+i).attr('checked','');
    } else {
        $('#points').text(count);
    }       
}