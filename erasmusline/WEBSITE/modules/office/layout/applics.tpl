    <link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<h2>Pending Student Application Forms</h2>
<table cellspacing="1">
        <caption>
            Students who filled in Student Application Form
        </caption>
        <tr>
            <th>
                Name
            </th>
            <th>
                View Student Application Form
            </th>
        </tr>
		{iteration:iApplics}
        <tr>
            <td>
            	<a href="{$hrefProfile}" class="userFoto" title="Photo user"><img src="{$hrefPhoto}" alt="Picture user" height="45" width="45" /></a>
                <a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
                <a href="{$url}" title="student application form">View</a>
            </td>
        </tr>
		{/iteration:iApplics}
    </table>