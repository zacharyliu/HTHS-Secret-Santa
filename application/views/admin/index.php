<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <?php if ($this->session->flashdata('admin')) echo $this->session->flashdata('admin'); //if there's an admin result, echo it?>
        <h1>Admin Panel</h1>

        <div class="row">
            <h2>Group Management</h2>
            <ul id="years" class="nav nav-tabs">
                <?php
                $year = $first_year; //don't override first_year variable
                while ($year <= $current_year) {
                    if ($year != $current_year) //only add the active class to the most recent year
                        echo '<li><a href="#' . $year . '" data-toggle="tab">' . $year . '</a></li>';
                    else echo '<li class="active"><a href="#' . $year . '" data-toggle="tab">' . $year . '</a></li>';
                    $year++;
                }?>
            </ul>
            <div class="tab-content">
                <?php
                $year = $first_year; //reset year variable
                while ($year <= $current_year){
                    $count = false; //whether groups exist for the curent year
                    if ($year != $current_year) //only add the active class to the most recent year
                        echo '<div class="tab-pane fade" id="' . $year . '">';
                    else echo '<div class="tab-pane fade active in" id="' . $year . '">';?>
                <table class="table table-bordered table-striped" id="groups">
                    <tr>
                        <th>Name</th>
                        <th>Members</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                    <?php

                    foreach ($groups as $group) {
                        if ($group->year == $year) { //only echo if matching year
                            $count = true;
                            ?>
                            <tr>
                                <td><?php echo $group->name ?></td>
                                <td><?php echo $group->memberCount ?></td>
                                <td><?php echo $group->description ?></td>
                                <td>
                                    <form method="post" action="/admin/pairCustom">
                                        <input type="hidden" name="code" value="<?php echo $group->code ?>">
                                        <button type="submit"
                                                class="btn btn-primary" <?php if ($group->paired || $group->year != $current_year) echo ' disabled' ?>>
                                            Run Pairing
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        <?php
                        }
                    }
                    if ($count == false) //if no groups exist
                    echo '<tr><td colspan=4>Nothing to show here...</td></tr></td>';
                    echo '</table></div>';
                    $year++;
                    }
                    ?>
            </div>
        </div>
        <div class="row">
            <h2>Template Groups</h2>
            <table class="table table-bordered table-striped" id="groups">
                <tr>
                    <th>Group Name</th>
                    <th>Group Code</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                <?php
                if ($templates != false) {//template groups present
                    foreach ($templates as $template) {
                        var_dump($template->name);
                ?>
                    <tr>
                        <td><?php echo $template->name ?></td>
                        <td><?php echo $template->code ?></td>
                        <td><?php echo $template->description ?></td>
                    </tr>
                <?php    }
                }
                else echo '<tr><td colspan=4>Nothing to show here...</td></tr>';
                ?>
            </table>
            <form class="form-inline" role="form">
                <div class="form-group">
                    <label class="sr-only" for="groupName">Name</label>
                    <input type="text" class="form-control" id="groupName" placeholder="Group Name">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="groupCode">Code</label>
                    <input type="text" class="form-control" id="groupCode" placeholder="Code">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="groupDescrip">Description</label>
                    <input type="text" class="form-control" id="groupDescrip" placeholder="Description">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
            <div><a href="/admin/lockold">Lock</a> last year's groups. This will make all groups
                from <?php echo $this->adminmod->getPrevYear() ?> unleaveable.
            </div>
        </div>

    </div>
</div>