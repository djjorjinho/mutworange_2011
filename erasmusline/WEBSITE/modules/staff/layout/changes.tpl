
<div class="mainDiv">
    <fieldset>
        
    <legend>Pending Changes of Learning Agreements</legend>
        
<table id="staffTable" >
        <tr>
            <th>
            </th>
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
                </td>
                <td>
                <a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
                <a href="{$url}" title="change of learning agreement">View</a>
            </td>
        </tr>
		{/iteration:iChanges}
    </table>
        </fieldset>
    </div>