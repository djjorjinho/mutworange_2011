<h2>Welcome {$user}</h2>

<h3>Your status</h3>
<p>Your latest action:{$action}</p>
<p>Status of action: {$status}</p>

<h3>What's next?</h3>
<ul>{$next}</ul>

<h3>Forms that you already filled in</h3>
{option:noForms}
<p>You didn't fill in any forms yet</p>
{/option:noForms}

<ul>
{iteration:iForms}
{$form}
{/iteration:iForms}
    </ul>

<h3>Take exams in your home University</h3>
{$exams}
	
<h3>Latest events</h3>
<ul>
{iteration:iEvents}
{$event}
{/iteration:iEvents}
    </ul>







