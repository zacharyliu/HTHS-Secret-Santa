<html>
<head>
<script>
function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
</script>
</head>
<body>
<?php //echo validation_errors('<p style="color:red;">', '</p>'); ?>

<?php echo form_open('secretsanta/survey'); ?>

<div style="margin: 50px 10px 10px">
<p><b>Almost there!</b> Just a few steps left.</p>
<br />
<p>1. Define a <b>4 digit numerical</b> pin to be used in encrypting your private key.  This ensures you are the only one who can view your partner. <b>DO NOT forget this. There is no way to recover your pin.</p>
<form>
<?php echo form_error('pin'); ?>
Pin (*): <input type="text" maxlength="4" size="4"name="pin" value="<?php echo set_value('pin');?>" onkeypress="validate(event)"  /> <br />
<?php echo form_error('pinconf'); ?>
Confirm Pin (*): <input type="text" maxlength="4" size="4" name="pinconf" onkeypress="validate(event)" /> <br />
<br />
<p>2. If you were provided with a group code, enter it below. Groups allow you to exchange gifts with other members of the same group.  Leave this field blank if you don't have a group code or wish to add one later.</p>
<?php echo form_error('group'); ?>
Group Code: <input type="text" maxlength="4" size="4" name="group" /> <br />
<br />
<br />
 
<input type="submit">

</form>
</div>
</body>
</html>
