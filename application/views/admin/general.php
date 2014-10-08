<div class="row">
    <h2>General Settings</h2>

    <p>Designate a date for partner assignements and for the gift exchange.
    <form role="form">
        <div class="form-group">
            <label for="partner-date">Partner Assignment Date</label>
            <div class='col-md-3 col-lg-2 input-group date' id='partner-date'>
                <input readonly type='text' class="form-control" data-date-format="MM/DD"/>
					<span class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</span>
            </div>
        </div>
        <div class="form-group">
            <label for="gift-date">Gift Exchange Date</label>
            <div class='col-md-3 col-lg-2 input-group date' id='gift-date'>
                <input readonly type='text' class="form-control" data-date-format="MM/DD"/>
					<span class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</span>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Save Changes</button>
        </div>
    </form>

</div>

<link href="<?php echo base_url('css/bootstrap-datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('js/moment.min.js')?>"></script>
<script src="<?php echo base_url('js/bootstrap-datetimepicker.min.js')?>"></script>
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