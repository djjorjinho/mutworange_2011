<div class="mainDiv">
    <h3>Residence detail</h3>
    <fieldset>
    <legend>Residence</legend>
<p>Prijs: {$price}</p>
<p>Street + Nr: {$streetNr}</p>
<p>City: {$city}</p>
<p>Postal Code: {$postalCode}</p>
<p>Country: {$country}</p>
<p>{$kitchen}</p>
<p>{$bathroom}</p>
<p>{$water}</p>
<p>{$elektricity}</p>
<p>{$internet}</p>
<p>Number of beds available: {$beds}</p>
<p>Description: {$description}</p>
<p>Television: {$television}</p>   
<p>Availability: {$available}</p>
</fieldset>
    <fieldset>
<legend>Owner</legend>
<p>Name: {$familyName} {$firstName}</p>
<p>Telephone: {$tel}</p>
<p>Email: <a href="mailto:{$email}">{$email}</a></p>
</fieldset>
{option:oReservation}
<fieldset>
<legend>Make Reservation</legend>
<form action="" method="post" enctype="multipart/form-data">
     <div class="TRdiv">               
		<input type="hidden" name="formAction" id="formAcom" value="doAcom" />
		<input class="button" name="btnAcom" id="btnAcom" type="submit" value="Fill in Accomodation Registration Form"/>
	</div> 
</form>
{/option:oReservation}
</fieldset>

</div>