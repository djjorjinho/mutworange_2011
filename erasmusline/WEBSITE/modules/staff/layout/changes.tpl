<h2>Pending Change of Learning Agreements</h2>
<table cellspacing="1">
        <caption>
            Students who applied for Change of Learning Agreemnts
        </caption>
        <tr>
            <th>
                Name
            </th>
            <th>
                View Change Of Learning Agreement
            </th>
        </tr>
		{iteration:iChanges}
        <tr>
            <td>
            	<a href="{$hrefProfile}" class="userFoto" title="Photo user"><img src="{$hrefPhoto}" alt="Picture user" height="45" width="45" /></a>
                <a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
                <a href="{$url}" title="change of learning agreement">View</a>
            </td>
        </tr>
		{/iteration:iChanges}
    </table>