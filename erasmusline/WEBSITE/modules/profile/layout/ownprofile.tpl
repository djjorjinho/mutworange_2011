<div class="mainDiv">
    <h3>Profile of {$fName} {$faName}</h3>
<fieldset>
    <legend>General Information</legend>
<ul>
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

    <fieldset>
        <legend>Erasmus info</legend>
<img src="{$profile}" alt="profile" height="200" width="200" />
<p>Your current Erasmus progress<p>
<table>
    <tr>
        <td></td>
        <td><span class="progressBar" id="pb1"></span></td>
    </tr>
</table>
        </fieldset>
    <fieldset>
<legend>Period</legend>

<ul>
<li>
    Start date: {$start}
</li>
<li>
    End date: {$end}
</li>
</ul>
        </fieldset>
    <fieldset>
<legend>Where from - where to</legend>
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
</fieldset>
<fieldset>
<legend>What?</legend>
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
            <li>Total: {$total}
    </ul>
</li>
</ul>
</fieldset>


</div>


