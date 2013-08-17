<style>
    .flash {
        padding: 5px;
        border-radius: 2px;
        background-color: lightyellow;
        border: 1px solid yellow;
        font-weight: bold;
    }
</style>

<?php
$flashdata = $this->session->flashdata('admin');
if ($flashdata) {
    echo '<p class="flash">' . $flashdata . '</div>';
}
?>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <h1>Admin Panel</h1>

        <div class="row">
            <h2>Group Management</h2>
            <table class="table table-bordered table-striped" id="groups">
                <tr>
                    <th>Name</th>
                    <th>Members</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                <?php
                foreach ($groups as $group) {
                    ?>

                    <tr>
                        <td><?php echo $group->name ?></td>
                        <td><?php echo $group->memberCount ?></td>
                        <td><?php echo $group->description ?></td>
                        <td>
                            <form method="post" action="<?php echo base_url('admin/pairCustom'); ?>">
                                <input type="hidden" name="code" value="<?php echo $group->code ?>">
                                <button type="submit"<?php if ($group->paired) echo ' disabled' ?>>Run Pairing</button>
                            </form>
                        </td>
                    </tr>

                <?php
                }
                ?>
            </table>
        </div>
        <div class="row">
            <h2>Garbage Collection</h2>
            <div><a href="/admin/lockold">Lock</a> last year's groups. This will make all groups from <?php echo $this->adminmod->getPrevYear()?> unleaveable.</div>
        </div>

    </div>
</div>