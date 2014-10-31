<div class="container">
<div class="row">
    <?php if (($this->session->flashdata('setup'))) echo $this->session->flashdata('setup'); //if there's a result message, show it
    echo form_error('admin-email');
    echo form_error('admin-email-confirm');
    echo form_error('site-name');
    echo form_error('domain-restriction');
    ?>
    <br/>

    <h2>Secret Santa Setup</h2>
    <p>Welcome to the setup script! Before you proceed, please ensure that the schema in <code class="varName">database.sql</code> has been imported into the database you specified in the database configuration file.  After that has been completed, fill out the following information. All settings may be changed later.</p>
    <br/>

    <form role="form" method="post">
        <div class="row form-group">
            <label for="site-name">Admin Email</label>
            <p class="help-block">Enter the email address of the first user that will have admin privileges. Ensure this is correct, as this email address must be used to access the admin panel to add other admins and make site-wide changes.</p>
            <input required type="email" value="<?= set_value('admin-email'); ?>" class="form-control" id="site-name" name="site-name" placeholder="example@example.com">
            <p class="help-block">Confirm the admin email address.</p>
            <input required type="email" value="<?= set_value('admin-email-confirm'); ?>" class="form-control" id="site-name" name="site-name" placeholder="example@example.com">

        </div>
        <div class="row form-group">
            <label for="site-name">Site Name</label>
            <p class="help-block">Name of the site that will show up in the navbar.</p>
            <input required type="text" value="<?= set_value('site-name'); ?>" class="form-control" id="site-name" name="site-name" placeholder="HTHS Secret Santa">
        </div>
        <div class="row form-group">
            <label for="domain-restriction">Domain Restriction</label>
            <p class="help-block">Define a regex to only allow users from certain email domains to sign in. For example, use <code class="varName">/^[^@]+@example\.com$/</code> to only allow users from "example.com" to sign in. Exceptions can be manually set later.</p>
            <input type="text" value="<?= set_value('domain-restriction'); ?>" class="form-control" id="domain-restriction" name="domain-restriction" placeholder="/^[^@]+@example\.com$/">
        </div>
</div>
<div class="form-group">
    <button class="btn btn-primary" type="submit">Save Changes</button>
</div>
</form>

</div>
</div>