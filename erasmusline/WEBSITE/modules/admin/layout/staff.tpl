<div class="mainDiv">
    <h3>Admin Homepage</h3>
    <fieldset>
    <legend>Staff Members</legend>
    <table border="1" cellspacing="0">
        <tr>
            <th>
                Name
            </th>
            <th>
                Delete
            </th>
            <th>Password</th>
        </tr>
		{iteration:iStaff}
        <tr>
            <td>
            	<a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
                <a href="{$deleteUrl}" class="delete" title="Delete">Delete</a>
				
            </td>
            <td><a href="{$passUrl}" class = "Send" title="Send password">Send password</a></td>
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
    