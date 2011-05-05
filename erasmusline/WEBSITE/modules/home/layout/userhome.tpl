<h2>Welcome {$user}</h2>

<h3>Your status</h3>
<p>Your latest action:{$action}</p>
<p>Status of action: {$status}</p>

<h3>What's next?</h3>
<p>{$next}</p>

<h3>Forms that you already filled in</h3>
{option:noForms}
<p>You didn't fill in any forms yet</p>
{/option:noForms}

<ul>
{iteration:iForms}
{$form}
{/iteration:iForms}
    </ul>







