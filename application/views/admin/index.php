<style>
    .flash {
        padding: 5px;
        border-radius: 2px;
        background-color: lightyellow;
        border: 1px solid yellow;
        font-weight: bold;
    }
    #groups td, #groups th {
        padding: 5px;
    }
</style>

<?php
    $flashdata = $this->session->flashdata('admin');
    if ($flashdata) {
        echo '<p class="flash">' . $flashdata . '</div>';
    }
?>
<h1>Admin Panel</h1>
<p>
    <h2>Group Management</h2>
    <table id="groups">
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
</p>