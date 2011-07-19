
<div class="mainDiv">
    <h2> Add Residences </h2>
    <form action="" method="post" enctype="multipart/form-data" id="add" name="add">	
        <fieldset>
            <legend>Owner Information</legend>    
            <div class="TRdiv">
                <label for="familyName"><span>Last Name : </span></label>
                <input type="text" class="validate[required,custom[onlyLetterSp]] text-input" name="familyName" id="familyName"  onkeyup="lookup(this.value);" onclick="fill();" value="{$familyName|htmlentities}" />
                <span class="req" id="msgFamilyName">{$msgFamilyName|htmlentities}</span>	
            </div>

            <div class="suggestionsBox" id="suggestions" style="display: none;">
                <div class="suggestionList" id="autoSuggestionsList">
                    &nbsp;
                </div>
            </div> 
            <div class="TRdiv">
                <label for="firstName"><span>First Name : </span></label>
                <input type="text" class="validate[required,custom[onlyLetterSp]] text-input" name="firstName" id="firstName" value="{$firstName|htmlentities}" />
                <span class="req" id="msgFirstName">{$msgFirstName|htmlentities}</span>	
            </div> 
            <div class="TRdiv">
                <label for="email"><span>Email address : </span></label>
                <input type="text" class="validate[required,custom[email]] text-input" name="email" id="email" value="{$email|htmlentities}" />
                <span class="req" id="msgEmail">{$msgEmail|htmlentities}</span>	
            </div>    
            <div class="TRdiv">
                <label for="streetNr"><span>Street + NR : </span></label>
                <input type="text" class="validate[required,custom[onlyLetterNumber]] text-input" name="streetNr" id="streetNr" value="{$streetNr|htmlentities}" />
                <span class="req" id="msgStreetNr">{$msgStreetNr|htmlentities}</span>	
            </div>  
            <div class="TRdiv">
                <label for="city"><span>City</span></label>
                <input class="validate[required,custom[onlyLetterSp]] text-input" type="text" name="city" id="city" value="{$city|htmlentities}"/>
                <span class="req" id="msgCity">{$msgCity|htmlentities}</span>	
            </div>
            <div class="TRdiv">
                <label for="postalCode"><span>Postal Code</span></label>
                <input class="validate[required,maxSize[4]] text-input" type="text" name="postalCode" id="postalCode" value="{$postalCode|htmlentities}"/>
                <span class="req" id="msgPostalCode">{$msgPostalCode|htmlentities}</span>	
            </div>
            <div class="TRdiv">
                <label for="countryOwner"><span>Country : </span></label>
                <select name="countryOwner" >
                    {iteration:icountryOwner}
                    {$countryOwner}
                    {/iteration:icountryOwner}
                </select>
                <span class="req" id="msgCountryOwner">{$msgCountryOwner|htmlentities}</span>
            </div>
            <div class="TRdiv">        
                <label for="tel"><span>Telephone : </span></label>
                <input type="text" class="validate[required,custom[onlyNumberSp]] text-input" name="telephone" id="telephone" value="{$telephone|htmlentities}" />
                <span class="req" id="msgTelephone">{$msgTelephone|htmlentities}</span>	
            </div>
            <div class="TRdiv">
                <label for="mobilePhone"><span>Mobile Phone : </span></label>
                <input type="text" class="validate[required,custom[onlyNumberSp]] text-input" name="mobilePhone" id="mobilePhone" value="{$mobilePhone|htmlentities}" />
                <span class="req" id="msgMobilePhone">{$msgMobilePhone|htmlentities}</span>	
            </div>
        </fieldset>
        <fieldset>
            <legend>Residence Information</legend>    
            <div class="TRdiv">
                <label for="price"><span>Price </span></label>
                <input type="text" class="validate[required,custom[onlyNumberSp]]" name="price" id="price"  value="{$price|htmlentities}" />
                <span class="req" id="msgPrice">{$msgPrice|htmlentities}</span>	
            </div>    
            <div class="TRdiv">
                <label for="streetNrResidence"><span>Street + NR : </span></label>
                <input type="text" class="validate[required,custom[onlyLetterNumber]] text-input" name="streetNrResidence" id="streetNrResidence" value="{$streetNrResidence|htmlentities}" />
                <span class="req" id="msgStreetNrResidence">{$msgStreetNrResidence|htmlentities}</span>	
            </div>  
            <div class="TRdiv">
                <label for="cityResidence"><span>City</span></label>
                <input class="validate[required,custom[onlyLetterSp]] text-input" type="text" name="cityResidence" id="cityResidence" value="{$cityResidence|htmlentities}"/>
                <span class="req" id="msgCityResidence">{$msgCityResidence|htmlentities}</span>	
            </div>
            <div class="TRdiv">
                <label for="postalCodeResidence"><span>Postal Code</span></label>
                <input class="validate[required,custom[onlyLetterNumber]] text-input" type="text" name="postalCodeResidence" id="postalCodeResidence" value="{$postalCodeResidence|htmlentities}"/>
                <span class="req" id="msgPostalCodeResidence">{$msgPostalCodeResidence|htmlentities}</span>	
            </div>
            <div class="TRdiv">
                <label for="countryResidence"><span>Country : </span></label>
                <select name="countryResidence" >
                    {iteration:iCountryResidence}
                    {$countryResidence}
                    {/iteration:iCountryResidence}
                </select>
                <span class="req" id="msgCountryResidence">{$msgCountryResidence|htmlentities}</span>
            </div>
            <div class="TRdiv">
                <label for="beds"><span>Number of Beds</span></label>
                <input class="validate[required] text-input" type="text" name="beds" id="beds" value="{$beds|htmlentities}"/>
                <span class="req" id="msgBeds">{$msgBeds|htmlentities}</span>	
            </div>
            <div class="radioResidences">
                <div class="radioResidence">
                <label class="description" for="kitchen">Kitchen :</label>
                Communal<input type="radio" {$kitchenYes} id="kitchenYes" name="kitchen" value="1" class="validate[required] radio"  />
                               Personal<input type="radio" {$kitchenNO} id="kitchenNo" name="kitchen" value="0" class="validate[required] radio" />
                               <span class="req" id="msgKitchen">{$msgKitchen|htmlentities}</span><br />
                </div><div class="radioResidence">
                    <label class="description" for="bathroom">Bathroom : </label>
                Communal<input type="radio" {$bathroomYes} id="bathroomYes" name="bathroom" value="1" class="validate[required] radio"  />
                               Personal<input type="radio" {$bathroomNo} id="bathroomNo" name="bathroom" value="0" class="validate[required] radio" />
                               <span class="req" id="msgBathroom">{$msgBathroom|htmlentities}</span><br />
                </div><div class="radioResidence"><label class="description" for="water">Water : </label>
                Included<input type="radio" {$waterYes} id="waterYes" name="water" value="1" class="validate[required] radio"  />
                               Not Included<input type="radio" {$waterNo} id="waterNo" name="water" value="0" class="validate[required] radio" />
                               <span class="req" id="msgWater">{$msgWater|htmlentities}</span><br />
                </div><div class="radioResidence"><label class="description" for="internet">Internet : </label>
                Included<input type="radio" {$internetYes} id="internetYes" name="internet" value="1" class="validate[required] radio"  />
                               Not Included<input type="radio" {$internetNo} id="internetNo" name="internet" value="0" class="validate[required] radio" />
                               <span class="req" id="msgInternet">{$msgInternet|htmlentities}</span><br />
                </div><div class="radioResidence"><label class="description" for="television">Television : </label>
                Included<input type="radio" {$televisionYes} id="televisionYes" name="television" value="1" class="validate[required] radio"  />
                               Not Included<input type="radio" {$televisionNo} id="televisionNo" name="television" value="0" class="validate[required] radio" />
                               <span class="req" id="msgTelevision">{$msgTelevision|htmlentities}</span><br />
                </div><div class="radioResidence"><label class="description" for="elektricity">Elektricity :</label>
                Included<input type="radio" {$elektricityYes} id="elektricityYes" name="elektricity" value="1" class="validate[required] radio"  />
                               Not Included<input type="radio" {$elektricityNo} id="elektricityNo" name="elektricity" value="0" class="validate[required] radio" />
                               <span class="req" id="msgElektricity">{$msgElektricity|htmlentities}</span>
            </div></div>

            <div class="TRdiv">
                <label for="description"><span>Description : </span></label>
                <textarea class="validate[required],custom[textarea]" type="text" name="description" id="description" cols="50" rows="5">{$description|htmlentities}</textarea>
                <span class="req" id="msgDescription">{$msgDescription|htmlentities}</span>	
            </div>
        </fieldset>

        <div class="TRdiv">               
            <input type="hidden" name="formAction" id="add" value="doAdd" />
            <input class="button" name="btnAdd" id="btnAdd" type="submit" value="Add Residence"/>
        </div>
        </form>
</div>