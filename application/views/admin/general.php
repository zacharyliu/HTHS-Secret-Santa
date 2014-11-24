<div class="row">
    <?php if (($this->session->flashdata('admin'))) echo $this->session->flashdata('admin'); //if there's a result message, show it
    echo form_error('partner-date');
    echo form_error('gift-date');
    echo form_error('max-groups');
    ?>
    <br/>

    <h2>General Settings</h2>
    <p>Modify these general settings based on event logistics. Any changes will be effective immdeiately.</p>
    <br/>

    <form role="form" method="post" action="<?php echo current_url(); //base for persistent forms?>">
        <div class="row form-group">
            <p>Designate a date for partner assignments and for the gift exchange. These dates are purely cosmetic. It is your responsibility to run pairing and enforce these deadlines.</p>
            <div class="col-md-4 col-lg-2">
                <label for="partner-date">Partner Assignment Date</label>
                <div class='input-group date' id='partner-date'>
                    <input required readonly type='text' value="<?= date_format($partner_date,"m/d"); ?>"class="form-control" name="partner-date" data-date-format="MM/DD"/>
					<span class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</span>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-4 col-lg-2">
                <label for="gift-date">Gift Exchange Date</label>

                <div class='input-group date' id='gift-date'>
                    <input required readonly type='text' value="<?= date_format($gift_date,"m/d"); ?>" class="form-control" name="gift-date" data-date-format="MM/DD"/>
					<span class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</span>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <p>Max number of groups that a user can be in.</p>
            <div class="col-md-4 col-lg-2">
            <label for="max-groups">Max Groups</label>
            <input required type="number" min="1" max="20" value="<?= $max_groups;?>"class="form-control" id="max-groups" name="max-groups">
                </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Save Changes</button>
        </div>
    </form>

</div>

<link href="<?php echo base_url('css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('js/moment.min.js') ?>"></script>
<script src="<?php echo base_url('js/bootstrap-datetimepicker.min.js') ?>"></script>
<script type="text/javascript">
    $(function () {
        $('#partner-date').datetimepicker({
            pickTime: false
        });
        $('#gift-date').datetimepicker({
            pickTime: false
        });
    });
</script>