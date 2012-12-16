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
<h1>Admin Panel</h1>
<p>
    <form method="post" action="<?php echo base_url('admin/pairCustom'); ?>">
        <label>
            Run pairing on group:
            <input type="text" name="code">
        </label>
        <button type="submit">Submit</button>
    </form>
</p>