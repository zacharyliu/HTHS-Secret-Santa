<?php //echo validation_errors('<p style="color:red;">', '</p>'); ?>

<div style="margin: 50px 10px 10px">
<?php if (($this->session->flashdata('result'))) echo $this->session->flashdata('result'); //if there's a result message, show it?>
<br />
<h3>My groups </h3>
<p>You are currently in <?php echo ($this->datamod->countPersonGroups($this->session->userdata('name')));?>/5 groups.</p>
<table style="font-size:12px;" border="1" cellpadding="5">
	<tr style="font-weight:bold;">
		<th>Group Name</th>
		<th>Group Code</th>
		<th># of Members</th>
		<th>Partner</th>
		<th>Description</th>
		<th>Options</th>
	</tr>
	<?php echo $this->datamod->drawMemberGroups($this->session->userdata('name')); //draw group and partner data?>
</table>
<div style="padding: 3px 0 0 0;font-size:9px">*Groups must have at least 5 members to be valid.</div></div>

<br />

<?php echo form_open('profile/groupcode'); ?>
<div style="margin: 50px 10px 10px">
<h3>Join a group</h3>
<p>Enter a group code below to join a group.</p>
<form>
<?php //if (($this->session->flashdata('groupCode'))) echo $this->session->flashdata('groupCode'); //depreciated, moved to result up top?>
<?php echo form_error('group'); ?>
Group Code: <input type="text" maxlength="4" size="4" name="group" /> <span>&nbsp;&nbsp;&nbsp;
<input value="add group" type="submit">
</form>
</div>
<?php echo form_close();?>

<br />
<br />

<?php echo form_open('profile/addgroup'); ?>
<div style="margin: 0px 10px 10px">
<h3>Create a group</h3>
<p>Create a group to gift exchange with some friends.</p>
<form>
<?php //if (($this->session->flashdata('groupCreate'))) echo $this->session->flashdata('groupCreate');//depreciated, moved to result up top?>

<?php echo form_error('group_name'); ?>
Group Name: <input type="text" maxlength="50" size="50" name="group_name" /> <span>&nbsp;&nbsp;&nbsp;
<input value="create group" type="submit">
</form>
</div>
<?php echo form_close();?>

<br />
<div style="margin: 0px 10px 10px">
<h3>Settings</h3>
<p><a href="<?php echo base_url('profile/resetPin');?>">Reset</a> my pin.</p>
<br />

