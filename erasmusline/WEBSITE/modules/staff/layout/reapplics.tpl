<h2>Pending Retried Student Application Forms</h2>
<table cellspacing="1">
        <caption>
            Students who made a new Application Form
        </caption>
        <tr>
            <th>
                Name
            </th>
            <th>
                View Student Application Form
            </th>
        </tr>
		{iteration:iReApplics}
        <tr>
            <td>
            	<a href="{$hrefProfile}" class="userFoto" title="Photo user"><img src="{$hrefPhoto}" alt="Picture user" height="45" width="45" /></a>
                <a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
                <a href="{$url}" title="student application form">View</a>
            </td>
        </tr>
		{/iteration:iReApplics}
    </table>