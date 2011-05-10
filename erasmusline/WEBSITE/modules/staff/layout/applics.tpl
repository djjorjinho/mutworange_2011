<h2>Pending Student Application Forms</h2>
<table cellspacing="1">
        <caption>
            <h2>Students who filled in Precandidate</h2>
        </caption>
        <tr>
            <th>
                Name
            </th>
            <th>
                View Precandidate
            </th>
        </tr>
		{iteration:iApplics}
        <tr>
            <td>
            	<a href="{$hrefProfile}" class="userFoto" title="Photo user"><img src="{$hrefPhoto}" alt="Picture user" height="45" width="45" /></a>
                <a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
                <a href="{$url}" title="precandidate">View</a>
            </td>
        </tr>
		{/iteration:iApplics}
    </table>