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
<?php echo validation_errors('<p style="color:red;">', '</p>'); ?>

<?php echo form_open('secretsanta/survey'); ?>

<div style="margin: 50px 10px 10px">
<p>Define a <b>4 digit numerical</b> pin to be used in encrypting your private key.  This ensures you are the only one who can view your partner. </p>
<form>
Pin: <input type="text" maxlength="4" name="pin" onkeypress="validate(event)"> <br />
Pin (Confirm): <input type="text" maxlength="4" name="pinconf" onkeypress="validate(event)"> <br />
<br />
<input type="submit">

</form>
</div>
</body>
</html>
