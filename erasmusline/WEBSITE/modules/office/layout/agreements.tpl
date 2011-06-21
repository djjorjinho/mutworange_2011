
<div class="mainDiv">
<h3>Pending Learning Agreements</h3>
<fieldset>
<table id="staffTable">
        <tr>
            <th>
            </th>
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
                </td>
                <td>
                <a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
                <a href="{$url}" title="learning agreement">View</a>
            </td>
        </tr>
		{/iteration:iAgreements}
    </table>
    </fieldset>
</div>