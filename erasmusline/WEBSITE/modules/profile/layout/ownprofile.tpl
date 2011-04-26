<h2>{$fName} {$faName}<span> ({$userLevel})</span></h2>

<h3>About {$fName}</h3>

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
    </ul>

<h3>Erasmus info</h3>
<img src="../www/core/img/googlemaps.jpeg" alt="google" height="200" width="200" />
<h4>Your current Erasmus progress<h4>
<table>
    <tr>
        <td></td>
        <td><span class="progressBar" id="pb1"></span></td>
    </tr>
</table>

<h4>Period</h4>

<ul>
<li>
    Start date: {$start}
</li>
<li>
    End date: {$end}
</li>
</ul>
<h4>Where from - where to</h4>
<ul>
<li>
    Home institution: {$host}
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

<h4>What?</h4>
<li>
   Study: {$study}
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

<h3>Lodging</h4>
<h4>Period</h4>
<ul>
    <li>
        From: {$from}
    </li>
    <li>
        Untill: {$till}
    </li>
</ul>

<h4>Where</h4>
<ul>
    <li>Postalcode: {$postal}</li>
    <li>City: {$city}</li>
    <li>Street + Nr: {$street}</li>
</ul>

<h4>Info</h4>
<ul>
    <li>Price: {$price} ({$water_elek_included} - {$internet}</li>
    <li>Personal kitchen: {$kitchen}</li>
    <li>Personal bathroom: {$bathroom}</li>
    <li>Number of beds: {$beds}</li>
    <li>Description: {$descrip}</li>
</ul>


