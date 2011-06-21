<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
    <h3>Admin Homepage</h3>
    <fieldset>
    <legend><h3>Staff Members</h3></legend>
    <table border="1" cellspacing="0">
        <tr>
            <th>
                Name
            </th>
            <th>
                Delete
            </th>
        </tr>
		{iteration:iStaff}
        <tr>
            <td>
            	<a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
                <a href="{$deleteUrl}" class="delete" title="Delete">Delete</a>
				
            </td>
        </tr>
		{/iteration:iStaff}
    </table>
    </fieldset>
    <form action="" method="post" enctype="multipart/form-data" id="add" name="add">
        <div class="TRdiv">               
            <input type="hidden" name="formAction" id="add" value="doAdd" />
            <input class="button" name="btnAdd" id="btnAdd" type="submit" value="Add Staff Member"/>
        </div>
    </form>
    
    </div>
    