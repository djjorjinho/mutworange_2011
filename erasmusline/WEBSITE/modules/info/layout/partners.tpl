<div class="mainDiv">
<h3>Partners info</h3>

    {iteration:iPartners}
        <fieldset>
    <legend>{$instName}</legend>
        <p>Street + NR: {$instStreetNr}</p>
        <p>City : {$instCity}</p>
        <p>Postal Code: {$instPostalCode}</p>
        <p>Telephone: {$tel}</p>
        <p class="info">Website : <a href="{$instWebsite}">{$instName}</a></p>
    </fieldset>
    {/iteration:iPartners}
</div>