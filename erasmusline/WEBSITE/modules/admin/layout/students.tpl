<table border="1" cellspacing="0">
        <caption>
            <h2>Students who are taking part in Erasmus</h2>
        </caption>
        <tr>
            <th>
                Name
            </th>
            <th>
                Delete/Password
            </th>
        </tr>
		{iteration:iStudents}
        <tr>
            <td>
            	<a href="{$hrefProfile}" class="userFoto" title="Photo user"><img src="{$hrefPhoto}" alt="Picture user" height="45" width="45" /></a>
                <a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
                <a href="{$deleteUrl}" class="delete" title="Delete">Delete</a>
				<a href="{$passUrl}" class = "Send" title="Send password">Send password</a>
            </td>
        </tr>
		{/iteration:iStudents}
    </table>