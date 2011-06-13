<table border="1" cellspacing="0">
        <caption>
            <h2>Courses List</h2>
        </caption>
        <tr>
            <th>
                Name
            </th>
            <th>
                Delete/Edit
            </th>
        </tr>
		{iteration:iCourses}
        <tr>
            <td>
                {$name}
            </td>
            <td>
                <a href="{$deleteUrl}" class="delete" title="Delete">Delete</a>
				<a href="{$editUrl}" class = "Send" title="Edit Data">Edit</a>
            </td>
        </tr>
		{/iteration:iCourses}
    </table>
    <p><a href="index.php?module=institution&view=newcourse" title="New course">Add course</a></p>