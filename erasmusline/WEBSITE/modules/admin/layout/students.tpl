<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
    <h3>Admin Homepage</h3>
    <fieldset>
    <legend>Students who are taking part in Erasmus</legend>
    <table border="1" cellspacing="0">
        <tr>
            <th>
                Name
            </th>
            <th>
                Delete
            </th>
            <th>
                Password
            </th>
        </tr>
		{iteration:iStudents}
        <tr>
            <td class="userFoto">
            	<a href="{$hrefProfile}" class="test">{$name}</a>
            </td>
            <td>
                <a href="{$deleteUrl}" class="delete" title="Delete">Delete</a>
				
            </td>
            <td>
                <a href="{$passUrl}" class = "Send" title="Send password">Send password</a>
            </td>
        </tr>
		{/iteration:iStudents}
    </table>
    
</fieldset>    
    </div>