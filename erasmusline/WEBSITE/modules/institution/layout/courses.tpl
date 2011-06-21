<div class="mainDiv">
            <h3>Courses List</h3>
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
		{iteration:iCourses}
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
		{/iteration:iCourses}
    </table>
    <p><a href="index.php?module=institution&view=newcourse" title="New course">Add course</a></p>
    </div>