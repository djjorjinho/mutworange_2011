    <link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
<h3>Pending Extend Mobility Period</h3>
<fieldset>
<table id="staffTable">
        <tr>
            <th>
            </th>
            <th>
                Name
            </th>
            <th>
                View Extend Mobility Period
            </th>
        </tr>
		{iteration:iExtends}
        <tr>
            <td>
            	<a href="{$hrefProfile}" class="userFoto" title="Photo user"><img src="{$hrefPhoto}" alt="Picture user" height="45" width="45" /></a>
                </td>
                <td>
                <a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
                <a href="{$url}" title="student application form">View</a>
            </td>
        </tr>
		{/iteration:iExtends}
    </table>
    </fieldset>
</div>