
<div class="mainDiv">
    <fieldset>
        
    <legend><h3>Pending Precandidates</h3></legend>
<table id="staffTable" >
        <tr>
            <th>
            </th>
            <th>
                Name
            </th>
            <th>
                View Precandidate
            </th>
        </tr>
		{iteration:iPres}
        <tr>
            <td>
                <a href="{$hrefProfile}" class="userFoto" title="Photo user"><img src="{$hrefPhoto}" alt="Picture user" height="45" width="45" /></a>
            	</td>
                <td>
                <a href="{$hrefProfile}">{$name}</a>
            </td>
            <td>
                <a href="{$url}" title="precandidate">View</a>
            </td>
        </tr>
		{/iteration:iPres}
    </table>
    </fieldset>
</div>