<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <?php if ($this->session->flashdata('admin')) echo $this->session->flashdata('admin'); //if there's an admin result, echo it?>
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
                            <form method="post" action="/admin/pairCustom">
                                <input type="hidden" name="code" value="<?php echo $group->code ?>">
                                <button type="submit" class="btn btn-primary" <?php if ($group->paired) echo ' disabled' ?>>Run Pairing</button>
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