<script src="<?php echo base_url("/js/admin.js") ?>"></script>
<script src="<?php echo base_url("/js/memberlist.js") ?>"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php if ($this->session->flashdata('admin')) echo $this->session->flashdata('admin'); //if there's an admin result, echo it?>
            <h1>Admin Panel</h1>

            <div class="row">
                <h2>Group Management</h2>

                <p>Run pairing on designated groups. Click <a href="#" class="enable-pairing" id="<?php echo $current_year?>">here</a> to enable the pairing buttons for the current year. </p>
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
                    $count = false; //whether groups exist for the current year
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
                                    <td>
                                        <a data-toggle="modal" href="<?=base_url('group/' . $group->code . '/' . $group->year . '/membersModal')?>" data-target="#modal-member-list">
                                            <?php echo $group->memberCount ?>
                                        </a>
                                    </td>
                                    <td><?php echo $group->description ?></td>
                                    <td>
                                        <form method="post" action="/admin/pairCustom">
                                            <input type="hidden" name="code" value="<?php echo $group->code ?>">
                                            <button type="submit"
                                                    class="btn btn-primary pairing <?php echo $group->year?> disabled" <?php /*if ($group->paired || $group->year != $current_year) echo ' disabled'*/ ?> >
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

                <p>These are default groups that are available to join at any time. These groups are persistent and are
                    not removed when there are no users in them. Any edits made will be reflected in the group
                    properties even after creation. Deleting a template group will not delete the actual group if it is
                    already created.</p>
                <table class="table table-bordered table-striped" id="groups-templates">
                    <tr>
                        <th>Group Code</th>
                        <th>Group Name</th>
                        <th>Description</th>
                        <th>Private</th>
                        <th width="210px">Actions</th>
                    </tr>
                    <?php
                    if ($templates != false) { //template groups present
                        foreach ($templates as $template) {
                            if ($template->exists == true)
                                $exists = 'disabled';
                            else $exists = "";
                            ?>
                            <tr class="group" id="<?php echo $template->code ?>">
                                <td class="groupcode"><?php echo $template->code ?></td>
                                <td class="groupname"><?php echo $template->name ?></td>
                                <td class="description"><?php echo $template->description ?></td>
                                <td class="privacy"><?php echo $template->private ?></td>
                                <td class="actions">
                                    <button type="button" class="create btn btn-success"<?php echo $exists ?>>Create
                                    </button>
                                    <button type="button" class="edit btn btn-warning">Edit</button>
                                    <button type="button" class="delete btn btn-danger">Delete</button>
                                </td>
                            </tr>
                        <?php
                        }
                    } else echo '<tr id="empty-templates"><td colspan=5>Nothing to show here...</td></tr>';
                    ?>
                </table>
                <form id="newgroupform" class="form-inline" role="form">
                    <div class="form-group">
                        <label class="sr-only" for="groupCode">Code</label>
                        <input type="text" class="form-control" id="groupCode" maxlength="4" placeholder="Code">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="groupName">Name</label>
                        <input type="text" class="form-control" id="groupName" maxlength="50" placeholder="Group Name">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="groupDescrip">Description</label>
                        <input type="text" class="form-control" id="groupDescrip" maxlength="150"
                               placeholder="Description">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input id="privacy" type="checkbox"> Private Group
                        </label>
                    </div>
                    <button type="button" id="createGroup" class="btn btn-default disabled">Submit</button>
                </form>
                <!--<div><a href="/admin/lockold">Lock</a> last year's groups. This will make all groups
                from <?php echo $this->adminmod->getPrevYear() ?> unleaveable.-->
            </div>
        </div>

    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit-label"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Group Template Entry</h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <label for="modal-edit-code">Group Code</label>
                        <input type="text" class="form-control" placeholder="Group Code" id="modal-edit-code" disabled>
                    </div>

                    <div class="form-group">
                        <label for="modal-edit-name">Group Name</label>
                        <input type="text" class="form-control" id="modal-edit-name" maxlength="50"
                               placeholder="Group Name">
                    </div>
                    <div class="form-group">
                        <label for="modal-edit-description">Group Description</label>
                        <input type="text" class="form-control" id="modal-edit-description" maxlength="150"
                               placeholder="Group Description">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input id="modal-edit-privacy" type="checkbox"> Private Group
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" id="modal-edit-btn-save" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
</div>

<!-- Member List Modal -->
<div class="modal fade" id="modal-member-list" tabindex="-1" role="dialog" aria-hidden="true"></div>