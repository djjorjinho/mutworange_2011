
<div class="mainDiv">
    <h3>Welcome to your home page, {$user}</h3>
<fieldset>
<legend>Your current status</legend>

<p>Your latest phase: {$action}</p>
<p>Status of action: {$status}</p>
</fieldset>

 <fieldset>
    <legend>What is the next step in your Eramus process</legend>
<ul>{$next}</ul>
</fieldset>
<fieldset>
<legend>Forms that you already filled in</legend>
{option:noForms}
<p>You didn't fill in any forms yet</p>
{/option:noForms}

<ul>
{iteration:iForms}
{$form}
{/iteration:iForms}
    </ul>
</fieldset>
    <fieldset>
        <legend>Take exams in your home university</legend>
        <div class="TRdiv">
{$exams}
        </div>
        </fieldset>
    <fieldset>
        <legend>Notifications of your latest events</legend>
<ul>
{iteration:iEvents}
{$event}
{/iteration:iEvents}
    </ul>
</fieldset>	
</div>







