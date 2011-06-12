    <link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
    <h3>Pending Precandidates</h3>
    <fieldset>
        <legend>Precandidate</legend>
        
<table border="1" cellspacing="0">
        <tr>
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