<h2>{$action}</h2>
<p>{{$message}</p>
<form action="{$formAction|htmlentities}" id="registreer" method="post" enctype="multipart/form-data">
    <p>
        <label>
            <a class="undo" href="index.php?module=admin&view=toconfirm" title="Cancel">Cancel</a>  
        </label>
        <input type="submit" id="btnOk" name="btnOk" value="{$bntAction}" />
        <input type="hidden" name="formAction" id="formAction" value="{$doAction}" />
    </p>
</form>