<link rel="stylesheet" href="./core/css/form.css" type="text/css" />

<div class="mainDiv">
    <fieldset id="leftInfo">
    <legend>Other info</legend>
    <ul class="info">
        <li><a href="index.php?module=info&amp;view=erasmus">About Erasmus</a></li>
        <li><a href="index.php?module=info&amp;view=erasmusLine">About the application</a></li>
        <li><a href="index.php?module=info&amp;view=partners">Partners</a></li>
        <li><a href="index.php?module=info&amp;view=faq">FAQ</a></li>    
    </ul>
</fieldset>
    <div id="infoInfo">

    <h3>{$instName}</h3>
    <fieldset>
        <legend>Contact Info</legend>
        <p>Street + NR: {$instStreetNr}</p>
        <p>City : {$instCity}</p>
        <p>Postal Code: {$instPostalCode}</p>
        <p>Telephone: {$instTel}</p>
        <p>Email: <a href="mailto:{$instEmail}">{$instEmail}</a></p>
        <p class="info">Website : <a href="{$instWebsite}">{$instName}</a></p>
    </fieldset>
    </div>



</div>