<div class="row">
    <?php if (($this->session->flashdata('admin'))) echo $this->session->flashdata('admin'); //if there's a result message, show it
    //echo form_error('site-name');
    ?>
    <br/>

    <h2>Advanced Settings</h2>
    <p>Modifying settings in this file will have immediate, catastrophic consequences. Make changes only if you know what you're doing!</p>
    <br/>

    <form role="form">
        <div class="row form-group">
            <p>Name of the site that will show up in the navbar.</p>
            <div class="col-md-4 col-lg-2">
                <label for="site-name">Site Name</label>
                <input type="text" value="<?= $site_name; ?>" class="form-control" id="site-name" name="site-name">
            </div>
        </div>
    </form>

</div>