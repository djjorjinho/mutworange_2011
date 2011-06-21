
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
<h3>Partners info</h3>

    {iteration:iPartners}
        <fieldset>
        <p><strong>{$instName}</strong></p>
        <p>Street + NR: {$instStreetNr}</p>
        <p>City : {$instCity}</p>
        <p>Postal Code: {$instPostalCode}</p>
        <p>Telephone: {$tel}</p>
        <p class="info">Website : <a href="{$instWebsite}">{$instName}</a></p>
    </fieldset>
    {/iteration:iPartners}
</div>
</div>