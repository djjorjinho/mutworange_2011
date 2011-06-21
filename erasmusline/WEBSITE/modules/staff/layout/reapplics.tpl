<div class="mainDiv">
    <fieldset>
        <legend>Retried Student Application Forms and Learning Agreements</legend>
        
<table border="1" cellspacing="0">
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
        </fieldset>
    </div>