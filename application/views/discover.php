<script src="<?=base_url('js/memberlist.js')?>"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <?php if (($this->session->flashdata('result'))) echo $this->session->flashdata('result'); //if there's a result message, show it?>
                <?php echo form_error('group'); ?>
                <?php echo form_error('group_name'); ?>
                <br/>
            </div>
            <div class="row">
                <h2>Discover</h2>

                <p>Discover public groups you can join. These groups may have special circumstances and conditions, so
                    be sure to read the description carefully before signing up.</p>
            </div>

                <h3>Trending</h3>
                <p>These are groups rapidly growing in popularity.</p>
                <table class="table table-hover table-bordered">
                    <tr>
                        <th>Group Name</th>
                        <th>Group Code</th>
                        <th># of Members</th>
                        <th>Description</th>
                        <th>Options</th>
                    </tr>
                    <?php
                    if ($trending !=false){
                    foreach ($trending as $group) {
                            if ($this->datamod->inGroup($this->session->userdata('id'),$group->code)){//disable the join button if user is in group
                                $disabled = "disabled";
                            }
                        else $disabled = "";
                            echo '<tr><td><i>' . $group->name . '</i></td>';
                            echo '<td>' . $group->code . '</td>';
                            echo '<td><a data-toggle="modal" href="' . base_url('group/' . $group->code . '/' . $group->year . '/membersModal') . '" data-target="#modal-member-list">' . $this->datamod->countMembers($group->code, $group->year) . '</a></td>';
                            echo '<td>' . $group->description . '</td>';
                            echo('<td><a href="'.base_url("/discover/joinGroup/".$group->code).'"><button type="button" class="btn btn-primary '.$disabled.'">Join</button></a></td>' );
                            echo '</tr>';
                    }}
                    else{
                        echo "<tr><td colspan='6'>there doesn't seem to be anything here...</td></tr>";
                    echo '</table></div>';
                        }
                    ?>
                </table>
            </div>
    </div>
</div>

<!-- Member List Modal -->
<div class="modal fade" id="modal-member-list" tabindex="-1" role="dialog" aria-hidden="true"></div>