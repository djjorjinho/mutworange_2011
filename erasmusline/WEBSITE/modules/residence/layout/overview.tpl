<div class="mainDiv">
<form action="" method="post" enctype="multipart/form-data" id="search">	
    
    <div class="TRdiv">
            <label for="country"><span>Country</span></label>
            <select name="country" value="{$selectedCountry}">
               {iteration:iCountry}
                     {$country}
               {/iteration:iCountry}
            </select>
	</div>
    

        <div class="TRdiv">               
		<input type="hidden" name="formAction" id="formRegister" value="doSearch" />
		<input class="button" name="btnSearch" id="btnSend" type="submit" value="Search"/>
	</div> 
    {$error}
{iteration:iResidences}
    <div class="TRdiv">
    <p>Country: {$country} </p>
    <p>Price:{$price} </p>
    <p>City: {$city}</p>
    <p>Postal Code: {$postalCode}</p>
    <p><a href="index.php?module=residence&view=detail&id={$id}">More Details</a></p>
    </div>
{/iteration:iResidences}
</div>