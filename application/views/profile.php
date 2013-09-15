<?php //echo validation_errors('<p style="color:red;">', '</p>'); ?>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="row">
            <?php if (($this->session->flashdata('result'))) echo $this->session->flashdata('result'); //if there's a result message, show it?>
            <?php echo form_error('group'); ?>
            <?php echo form_error('group_name'); ?>
            <br/>

            <div class="col-md-3">
                <h3><?php echo $this->session->userdata("name"); ?></h3>

                <div class="row">
                    <h4>Medals</h4>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row">
                    <h3>My groups </h3>

                    <p>You are currently
                        in <?php echo($this->datamod->countPersonGroups($this->session->userdata('id'))); ?>/5
                        groups.</p>
                    <div class="container">
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
                        <table class="table table-hover table-bordered">
                            <tr>
                                <th>Group Name</th>
                                <th>Group Code</th>
                                <th># of Members</th>
                                <th>Partner</th>
                                <th>Description</th>
                                <th>Options</th>
                            </tr>
                        <?php
                            foreach ($groups as $group) {
                                if ($group->year == $year){
                                    $count = true;
                                    echo '<tr><td><i>' . $group->name . '</i></td>';
                                    echo '<td>' . $group->code . '</td>';
                                    echo '<td>' . $this->datamod->countMembers($group->code,$group->year) . '</td>';
                                    echo '<td>' . $this->datamod->getPair($group->code, $this->session->userdata('id'),$group->year) . '</td>';
                                    echo '<td>' . $group->description . '</td>';
                                    echo($group->leaveable ? '<td><a href="' . base_url('profile') . '/rm/' . $group->code . '">[leave]</a>&nbsp;</td>' : "<td></td>");
                                    echo '</tr>';
                            }
                            }
                            if ($count == false) //if no groups exist
                                echo "<tr><td colspan='6'>there doesnt seem to be anything here...</td></tr>";
                        echo '</table></div>';
                        $year++;
                        }
                        ?>
                    </table>
                    <div style="padding: 3px 0 0 0;font-size:9px">*Groups must have at least 5 members to be valid.
                    </div>
                </div>
                        </div>

                <br/>

                <h3>Join a group</h3>

                <form role="form" method="post"
                      action="<?php echo base_url('profile/groupcode'); // @todo base is needed since persistent forms?>">
                    <p>Enter a group code below to join a group.</p>
                    <label for="inputGroupCode">Group Code: </label>
                    <input type="text" class="form-custom" maxlength="4" size="4" name="group" id="inputGroupCode"
                           placeholder="">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-default" type="submit">Add Group</button>
                </form>


                <br/>
                <br/>


                <h3>Create a group</h3>

                <form method="post"
                      action="<?php echo base_url('profile/addgroup'); //@todo base is needed since persistent form?>">
                    <p>Create a group to gift exchange with some friends.</p>
                    <label for="inputGroupName">Group Name: </label>
                    <input type="text" class="form-custom" maxlength="50" size="50" name="group_name"
                           id="inputGroupName"
                           placeholder="">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-default" type="submit">Create Group</button>
                </form>
                <br/>

                <div class="row">
                    <h3>Settings</h3>

                    <p><a href="<?php echo base_url('profile/resetPin'); ?>">Reset</a> my pin.</p>
                    <br/>
                </div>
            </div>
        </div>
    </div>
</div>