
<div class="mainDiv">
    <h3>Profile of {$fName} {$faName}</h3>
    
<fieldset>
    <legend>General Information</legend>
    <img id="profile" src="{$profile}" alt="profile" height="200" width="200" />
<ul class="personalInfo">
        <li>
            Full name: {$fName} {$faName}
        </li>
        <li>
            Sex: {$sex}
        </li>
        <li>
            Nationality: {$nationality}
        </li>
        <li>
            City: {$city}
        </li>
        <li>
            Postalcode: {$postal}
        </li>
        <li>
            City: {$city}
        </li>
        <li>
            Userlevel: {$userLevel}
        </li>
    </ul>

</fieldset>

{option:oStudent}
<fieldset>
<legend>Erasmusinfo</legend>
<p><strong>Period</strong></p>
<ul>
<li>
    Start date: {$start}
</li>
<li>
    End date: {$end}
</li>
</ul>

<p><strong>Where from - where to</strong></p>
<ul>
<li>
    Home institution: {$home}
</li>
<li>
    Home coordinator: {$hCooordinator}
</li>
<li>
    Host institution: {$destination}
</li>
<li>
    Host Coordinator {$dCoordinator}
</li>
</ul>

<p><strong>What?</strong></p>
<ul>
<li>
   Education: {$study}
</li>
<li>
    Courses:
    <ul>
        {iteration:iCourses}
            <li>{$course}: {$ects}</li>
        {/iteration:iCourses}
            <li>Total: {$total}</li>
    </ul>
</li>
</ul>

</fieldset>
{/option:oStudent}
    
{option:oOthers}
    
    
    <fieldset>
        <legend><h3>Forms the student filled in</h3></legend>
        
        {option:noForms}
<p>Student didn't fill in any forms yet</p>
{/option:noForms}
    <ul>
{iteration:iForms}
{$form}
{/iteration:iForms}
    </ul>
</fieldset>
    {/option:oOthers}
</div>


