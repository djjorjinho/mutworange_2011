<h2>Admin panel</h2>
<p>Here you can add new staff members or delete them</p>

<caption>
            <h2>Staff</h2>
        </caption>
        <tr>
            <th>
                Name
            </th>
            <th>
                Delete/Password
            </th>
        </tr>
		{iteration:iStaff}
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
		{/iteration:iStaff}
    </table>
    
    <p><a href="index.php?module=register&view=register" title="New user">New staff</a></p>
    