<h2>Hee jij daar! ;)</h2>

<form method="post" action="index.php?module=infox">
<input type="hidden" name="table" value="users">
<input type="hidden" name="idUni" value="4">
<input type="hidden" name="data[familyName]" value="Kirk">
<input type="hidden" name="data[firstName]" value="Captain">
<input type="hidden" name="data[country]" value="ESP">
<input type="hidden" name="data[userLevel]" value="4">
<input type="hidden" name="data[institutionID]" value="5">
<input type="submit" value="Send Single User">
</form>

<form method="post" action="index.php?module=infox">
<input type="hidden" name="table" value="users">
<input type="hidden" name="idUni" value="4">
<input type="hidden" name="data[0][familyName]" value="Kirk">
<input type="hidden" name="data[0][firstName]" value="Captain">
<input type="hidden" name="data[0][country]" value="ESP">
<input type="hidden" name="data[0][userLevel]" value="4">
<input type="hidden" name="data[0][institutionID]" value="5">
<input type="hidden" name="data[1][familyName]" value="Kirk2">
<input type="hidden" name="data[1][firstName]" value="Captain2">
<input type="hidden" name="data[1][country]" value="ESP">
<input type="hidden" name="data[1][userLevel]" value="4">
<input type="hidden" name="data[1][institutionID]" value="5">
<input type="submit" value="Send Multiple User">
</form>

important for file upload: the have to be in the path which is specified in /modules/infox/infox.php
it will be stored in /modules/infox/airport/upload/ which has to be created because git didn't want to commit (?!?)
If it should be another folder, the path has to be changed in /airport/airport.php
<form method="post" action="index.php?module=infox">
<input type="hidden" name="file" value="test.txt" />
<input type="hidden" name="idUni" value="4" />
<input type="submit value="sending File" />
</form>