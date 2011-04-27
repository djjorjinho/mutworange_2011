<form action="" id="toconfirm" method="post" enctype="multipart/form-data" >
<table border="1" cellspacing="0">
        <caption>
            <h2>Students pre-candidate to be confirmed</h2>
        </caption>
        <tr>
            <th>
                Name
            </th>
            <th>
                Accept/Deny
            </th>
        </tr>
		{iteration:iStudents}
        <tr>
            <td>
            	<a href="{$hrefProfile}" class="userFoto" title="Photo user"><img src="{$hrefPhoto}" alt="Picture user" height="45" width="45" /></a>
                <a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
            <span>Accept</span><input class="validate[required] radio" type="radio" name="accepted{$i}" value="1" id="radio2{$i}" {$acceptedYes} />
            <span>Deny</span><input type="radio" class="validate[required] radio" name="accepted{$i}" value="0" id="radio0{$i}" {$acceptedNo} />
            <span>Wait</span><input type="radio" class="validate[required] radio" name="accepted{$i}" value="0" id="radio1{$i}" {$acceptedWait} />
            </td>
        </tr>
		{/iteration:iStudents}
    </table>
    <div class="TRdiv">
		<input type="hidden" name="formAction" id="formStudents" value="doConfirm" />
		<input class="button" name="btnConfirm" id="btnCOnfirm" type="submit" value="Submit"/>
	</div>
    </form>