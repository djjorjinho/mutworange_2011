

<div class="mainDiv">
<div class="eval">
<form action="" method="post" enctype="multipart/form-data" id="evaluationt">   

    <fieldset>
 
<legend>Evaluation Questionnairy</legend>
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
    <legend>Institute</legend>
<ul>
<li>
    Start date: {$start}
</li>
<li>
    End date: {$end}
</li>
</ul>

<h3>Where from - where to</h3>
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

<h3>What?</h3>
<ul>
<li>
   Education: {$study}
</li>
</ul>
</fieldset>
<fieldset>               
 <legend><span>Motivation</span></legend>
 
 <table>
     <tr>
         <th>
         <td>Not Important</td>
         <td>Rather Important</td>
         <td>Neither</td>
         <td>Important</td>
         <td>Very Important</td>
         </th>
     </tr>
     
{iteration:iMotivation}
     <tr>
 <div class="radioResidence">
    {$motivation}     
</div>
     </tr>
{/iteration:iMotivation}        
</table>     
</fieldset>
<fieldset>               
 <legend><span>How</span></legend>

     
{iteration:iHow}
     
    {$how}     
     
{/iteration:iHow} 
</fieldset>     
    <fieldset>               
 <legend><span>Info</span></legend> 
 <table>
     <tr>
         <th>
         <td>Not Important</td>
         <td>Rather Important</td>
         <td>Neither</td>
         <td>Important</td>
         <td>Very Important</td>
         </th>
     </tr>     
{iteration:iInfo}
     <tr>
 <div class="radioResidence">
    {$info}     
</div>
     </tr>
{/iteration:iInfo}          
</table>     
 </fieldset>
 <fieldset>               
 <legend><span>Language Prepare</span></legend>
{iteration:iLanguagePrepare}
     
    {$languagePrepare}     
     
{/iteration:iLanguagePrepare} 
</fieldset>        
    <fieldset>               
 <legend><span>Language Prepare Where</span></legend>

     
{iteration:iLanguagePrepareWhere}
     
    {$languagePrepareWhere}     
     
{/iteration:iLanguagePrepareWhere} 
</fieldset>      
 <fieldset>               
 <legend><span>Language Knowledge</span></legend>
 
 <table>
     <tr>
         <th>
          <td>Weak</td>
         <td>Insufficient</td>
         <td>Sufficient</td>
         <td>Good</td>
         <td>Excellent</td>
         </th>
     </tr>
     
{iteration:iKnowledge}
     <tr>
 <div class="radioResidence">
    {$knowledge}     
</div>
     </tr>
{/iteration:iKnowledge}        
</table>     
</fieldset>       
    <fieldset>               
 <legend><span>Arrival</span></legend>
{iteration:iArrival}
     
    {$arrival}     
     
{/iteration:iArrival} 
</fieldset>     
 <fieldset>               
 <legend><span>Events</span></legend>

     
{iteration:iEvents}
     
    {$events}     
     
{/iteration:iEvents} 
</fieldset>   
 <fieldset>               
 <legend><span>Support</span></legend> 
 <table>
     <tr>
         <th>
         <td>Weak</td>
         <td>Insufficient</td>
         <td>Sufficient</td>
         <td>Good</td>
         <td>Excellent</td>
         </th>
     </tr>     
{iteration:iSupport}
     <tr>
 <div class="radioResidence">
    {$support}     
</div>
     </tr>
{/iteration:iSupport}          
</table>     
 </fieldset>   
 <fieldset>               
 <legend><span>Integration</span></legend> 
 <table>
     <tr>
         <th>
         <td>Weak</td>
         <td>Insufficient</td>
         <td>Sufficient</td>
         <td>Good</td>
         <td>Excellent</td>
         </th>
     </tr>     
{iteration:iIntegration}
     <tr>
 <div class="radioResidence">
    {$integration}     
</div>
     </tr>
{/iteration:iIntegration}          
</table>     
 </fieldset>
  <fieldset>               
 <legend><span>Residence</span></legend>

     
{iteration:iResidence}
     
    {$residence}     
     
{/iteration:iResidence} 
</fieldset>    
<fieldset>               
 <legend><span>Residence Discovery</span></legend>

     
{iteration:iResidenceDiscovery}
     
    {$residenceDiscovery}     
     
{/iteration:iResidenceDiscovery} 
</fieldset> 
    <fieldset>               
 <legend><span>Accomodation</span></legend> 
 <table>
     <tr>
         <th>
         <td>Weak</td>
         <td>Insufficient</td>
         <td>Sufficient</td>
         <td>Good</td>
         <td>Excellent</td>
         </th>
     </tr>     
{iteration:iAccomodation}
     <tr>
 <div class="radioResidence">
    {$accomodation}     
</div>
     </tr>
{/iteration:iAccomodation}          
</table>     
 </fieldset> 
 <fieldset>               
 <legend><span>Sufficient</span></legend>
 
 <table>
     <tr>
         <th>
         <td>Not Important</td>
         <td>Rather Important</td>
         <td>Neither</td>
         <td>Important</td>
         <td>Very Important</td>
         </th>
     </tr>
     
{iteration:iSufficient}
     <tr>
 <div class="radioResidence">
    {$sufficient}     
</div>
     </tr>
{/iteration:iSufficient}        
</table>     
</fieldset> 
 
 
 <fieldset>               
 <legend><span>Scolarship</span></legend>

     
{iteration:iScolarship}
     
    {$scolarship}     
     
{/iteration:iScolarship} 
</fieldset>  
    <fieldset>               
 <legend><span>Finances</span></legend>

     
{iteration:iFinances}
     
    {$finances}     
     
{/iteration:iFinances} 
</fieldset>     
 <fieldset>               
 <legend><span>Contract</span></legend>
 
 <table>
     <tr>
         <th>
         <td>Yes</td>
         <td>No</td>
         </th>
     </tr>
     
{iteration:iContract}
     <tr>
 <div class="radioResidence">
    {$contract}     
</div>
     </tr>
{/iteration:iContract}        
</table>     
</fieldset>      
     <fieldset>               
 <legend><span>Exams</span></legend>

     
{iteration:iExams}
     
    {$exams}     
     
{/iteration:iExams} 
</fieldset>
         <fieldset>               
 <legend><span>Languages</span></legend>

     
{iteration:iArrayLanguages}
     
    {$arrayLanguage}     
     
{/iteration:iArrayLanguages} 
</fieldset>
    <fieldset>               
 <legend><span>Personal Motivation</span></legend>
 
 <table>
     <tr>
         <th>
         <td>Not Important</td>
         <td>Rather Important</td>
         <td>Neither</td>
         <td>Important</td>
         <td>Very Important</td>
         </th>
     </tr>
     
{iteration:iMotivationPersonal}
     <tr>
 <div class="radioResidence">
    {$motivationPersonal}     
</div>
     </tr>
{/iteration:iMotivationPersonal}        
</table>     
</fieldset>    
        <fieldset>               
 <legend><span>Learning</span></legend>
 
 <table>
     <tr>
         <th>
         <td>Not Important</td>
         <td>Rather Important</td>
         <td>Neither</td>
         <td>Important</td>
         <td>Very Important</td>
         </th>
     </tr>
     
{iteration:iLearning}
     <tr>
 <div class="radioResidence">
    {$learning}     
</div>
     </tr>
{/iteration:iLearning}        
</table>     
</fieldset>
    <fieldset>               
 <legend><span>Where</span></legend>
{iteration:iWhere}
     
    {$where}     
     
{/iteration:iWhere} 
</fieldset>      
        <fieldset>               
 <legend><span>Period</span></legend>
 
 <table>
     <tr>
         <th>
         <td>Too Short</td>
         <td>Sufficient</td>
         <td>Too Long</td>
         </th>
     </tr>
     
{iteration:iPeriod}
     <tr>
 <div class="radioResidence">
    {$period}     
</div>
     </tr>
{/iteration:iPeriod}        
</table>     
</fieldset> 
<fieldset>               
 <legend><span>Period</span></legend>
 
 <table>
     <tr>
         <th>
         <td>Complete</td>
         <td>Partial</td>
         <td>Not</td>
         </th>
     </tr>
     
{iteration:iObjective}
     <tr>
 <div class="radioResidence">
    {$objective}     
</div>
     </tr>
{/iteration:iObjective}        
</table>     
</fieldset>
    <fieldset>
        <legend>Problems</legend>        
        <textarea id="problems" name="problems" rows="7" cols="100">{$problems}</textarea>
    </fieldset>
     <fieldset>
        <legend>Suggestions</legend>        
        <textarea id="suggestions" name="suggestions" rows="7" cols="100">{$suggestions}</textarea>
    </fieldset>  
    
    
    
    <div class="TRdiv">               
		<input type="hidden" name="formAction" id="formRegister" value="doSubmit" />
		<input class="button" name="btnSend" id="btnSend" type="submit" value="Submit"/>
	</div>    
    
</form>
    </div>
    </div>