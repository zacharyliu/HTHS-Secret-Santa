<html>
<head>
    <script>
        function validate(evt) {
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
            var regex = /[0-9]|\./;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }
    </script>
</head>
<body>
<?php //echo validation_errors('<p style="color:red;">', '</p>'); ?>



<div style="margin: 50px 10px 10px">
    <?php if (isset($reset) && $reset == 1) { //if reset pin
        echo form_open('profile/resetPin');
        echo '<h3>Reset my Pin</h3><p>Fill out this form to reset your encryption pin.  <b>DO NOT</b> forget this, since it is required to view your partner name. You can reset your pin until the day registration closes.</p>';
    } else {
        echo form_open('secretsanta/survey');
        echo '<h3>Almost there!</h3><p>Just a few steps left.</p>
		<br />
				<p>1. Define a <b>4 digit numerical</b> pin to be used in encryption.  This must be set to participate and ensures you are the only one who can view your partner. <b>DO NOT</b> forget this. We do not store your pin, so it cannot be recovered.</p>';
    }?>
    <form>
        <?php echo form_error('pin'); ?>
        Pin (*): <input type="password" maxlength="4" size="4" name="pin" value="<?php echo set_value('pin'); ?>"
                        onkeypress="validate(event)"/> <br/>
        <?php echo form_error('pinconf'); ?>
        Confirm Pin (*): <input type="password" maxlength="4" size="4" name="pinconf" onkeypress="validate(event)"/>
        <br/>
        <br/>
        <!--<p>2. If you were provided with a group code, enter it below. Groups allow you to exchange gifts with other members of the same group.  Leave this field blank if you don't have a group code or wish to add one later.</p>
<?php //echo form_error('group'); ?>
Group Code: <input type="text" maxlength="4" size="4" name="group" /> <br />
<br />-->
        <br/>

        <input type="submit">

    </form>
</div>
</body>
</html>
