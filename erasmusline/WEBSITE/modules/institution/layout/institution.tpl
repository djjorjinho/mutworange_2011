<h2>Institution Management</h2>


<table border="1" cellspacing="0">
{iteration:iInst}
        <tr>
            <th>
                Institution Email
            </th>
            <th>
                Institution Name
            </th>
            <th>
                Institution Street Number
            </th>
            <th>
                Institution City
            </th>
            <th>
                Institution Postal Code
            </th>
            <th>
                Institution Telephone
            </th>
            <th>
                Institution Fax
            </th>
            <th>
                Institution Description
            </th>
            <th>
                Institution Website
            </th>
            <th>
                Institution Type (trainee or study)
            </th>     
            <th>
            	
            </th>       
        </tr>

        <tr>
            <td>
                {$instemail}
            </td>
            <td>
                {$instname}
            </td>
            <td>
                {$inststnumb}
            </td>
            <td>
                {$instcity}
            </td>
            <td>
                {$instpostcode}
            </td>
            <td>
                {$insttele}
            </td>
            <td>
                {$instfax}
            </td>
            <td>
                {$instdesc}
            </td>
            <td>
                {$instweb}
            </td>
            <td>
                {$insttype}
            </td>
            <td>
				<a href="{$editUrl}" class = "Send" title="Edit Data">Edit</a>
            </td>
        </tr>
{/iteration:iInst}
    </table>

<p><a href="index.php?module=institution&view=courses" title="Manage Courses">Manage Courses</a></p>
<p><a href="index.php?module=institution&view=educations" title="Manage Educations">Manage Educations</a></p>
