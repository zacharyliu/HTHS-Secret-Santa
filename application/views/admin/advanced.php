<div class="row">
    <?php if (($this->session->flashdata('admin'))) echo $this->session->flashdata('admin'); //if there's a result message, show it
    echo form_error('site-name');
    echo form_error('domain-restriction');
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
                <input type="text" value="<?= $site_name; ?>" class="form-control" id="site-name" name="site-name">
            </div>
        </div>
        <div class="row form-group">
            <p>Define a regex to only allow users from certain email domains to sign in. For example, use <code class="varName">/^[^@]+@example\.com$/</code> to only allow users from "example.com" to sign in. Exceptions can be manually set in the <a href="<?=base_url("admin/groups")?>">Group Management</a> page.</p>
            <div class="col-md-4 col-lg-2">
                <label for="domain-restriction">Domain Restriction</label>
                <input type="text" value="<?= $domain_restriction; ?>" class="form-control" id="domain-restriction" name="domain-restriction">
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Save Changes</button>
        </div>
    </form>

</div>