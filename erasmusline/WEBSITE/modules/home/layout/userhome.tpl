<link rel="stylesheet" href="./core/css/form.css" type="text/css" />
<div class="mainDiv">
    <h2 class="homepage">Welcome to your home page, {$user}</h2>
<fieldset>
<legend>Your current status</legend>

<p>Your latest phase: {$action}</p>
<p>Status of action: {$status}</p>
</fieldset>

 <fieldset>
    <legend>What is the next step in your Eramus process</legend>
<ul>{$next}</ul>

{option:oAbroad}
<ul>
    {$abroad}
</ul>
{/option:oAbroad}

{option:oEmergency}
<p><strong>It's important for your stay in an other country that you have a European Emergency Card.<br />Please go the following link to apply for an Emergeny Card.</strong></p>
<p><a href="https://www.ehic.org.uk/Internet/startApplication.do" title="Emergeny Card">www.ehic.org.uk</a></p>
{/option:oEmergency}

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
	{$exams}      
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







