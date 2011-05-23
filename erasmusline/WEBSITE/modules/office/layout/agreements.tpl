<h2>Pending Learning Agreements</h2>
<table cellspacing="1">
        <caption>
            Students who filled in Learning Agreement
        </caption>
        <tr>
            <th>
                Name
            </th>
            <th>
                View Learning Agreement
            </th>
        </tr>
		{iteration:iAgreements}
        <tr>
            <td>
            	<a href="{$hrefProfile}" class="userFoto" title="Photo user"><img src="{$hrefPhoto}" alt="Picture user" height="45" width="45" /></a>
                <a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
                <a href="{$url}" title="learning agreement">View</a>
            </td>
        </tr>
		{/iteration:iAgreements}
    </table>