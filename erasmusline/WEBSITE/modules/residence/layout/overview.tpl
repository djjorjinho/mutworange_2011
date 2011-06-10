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
      <div class="TRdiv">               
		<input type="hidden" name="formAction" id="formRegister" value="doAddlink" />
		<input class="button" name="btnAddlink" id="btnSend" type="submit" value="Add"/>
	</div> 
    
    {$error}
            
                    <span class="{$disabledPrevious}">{$linkPrevious}</span>
                {iteration:iPagination}
                    {$text}
                {/iteration:iPagination}                    
                    <span class="{$disabledNext}">{$linkNext}</span>
            {iteration:iResidences}
                    <fieldset>
                    <legend>Residence</legend>
    <p>Price:{$price} </p>
    <p>City: {$city}</p>
    <p>Postal Code: {$postalCode}</p>
    <p class="linkResidence"><a href="index.php?module=residence&view=detail&id={$id}">More Details</a></p>
    </fieldset>
{/iteration:iResidences}   
                    
                    </div>