<div class="row">
    <?php if (($this->session->flashdata('admin'))) echo $this->session->flashdata('admin'); //if there's a result message, show it
    echo form_error('site-name');
    echo form_error('domain-restriction');
    echo form_error('admin-users');
    ?>
    <br/>

    <h2>Advanced Settings</h2>
    <p><em>Modifying settings in this file will have immediate, catastrophic consequences. Make changes only if you know what you're doing!</em></p>
    <br/>

    <form role="form" method="post">
        <div class="row form-group">
            <p>Name of the site that will show up in the navbar.</p>
            <div class="col-md-4 col-lg-2">
                <label for="site-name">Site Name</label>
                <input type="text" value="<?= set_value('site-name',$site_name); ?>" class="form-control" id="site-name" name="site-name">
            </div>
        </div>
        <div class="row form-group">
            <p>Define a regex to only allow users from certain email domains to sign in. For example, use <code class="varName">/^[^@]+@example\.com$/</code> to only allow users from "example.com" to sign in. Exceptions can be manually set in the <a href="<?=base_url("admin/groups")?>">Group Management</a> page.</p>
            <div class="col-md-4 col-lg-2">
                <label for="domain-restriction">Domain Restriction</label>
                <input type="text" value="<?= set_value('domain-restriction',$domain_restriction); ?>" class="form-control" id="domain-restriction" name="domain-restriction">
            </div>
        </div>
        <div class="row form-group">
            <p>Insert each admin user's email on a new line. Make sure not to delete yourself!</p>
            <div class="col-md-4 col-lg-2">
                <label for="domain-restriction">Admin Emails</label>
            <textarea class="form-control emails" id="admin-users" name="admin-users"><?php
                $admin_users = set_value('admin-users',$admin_users);
                if (is_string($admin_users)) {
                    $admin_users = explode("\r\n", $admin_users);
                }
                foreach ($admin_users as $email) {
                    echo $email . "\r\n";
                }
                ?></textarea>
                </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Save Changes</button>
        </div>
    </form>

</div>