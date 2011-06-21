<div class="mainDiv">

            <h3>Educations List</h3>
            <p></p>
<table border="1" cellspacing="0">
        <tr>
            <th>
                Name
            </th>
            <th>
                Delete
            </th>
            <th>
            	Edit           
            </th>
        </tr>
		{iteration:iEducations}
        <tr>
            <td>
                {$name}
            </td>
            <td>
                <a href="{$deleteUrl}" class="delete" title="Delete">Delete</a>
            </td>
            <td>
				<a href="{$editUrl}" class = "Send" title="Edit Data">Edit</a>
            </td>
        </tr>
		{/iteration:iEducations}
    </table>
    <p><a href="index.php?module=institution&view=neweducation" title="New education">Add education</a></p>
    
</div>