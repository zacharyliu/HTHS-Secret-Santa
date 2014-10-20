<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup extends CI_Migration
{

    public function up()
    {

        $global_vars = array(
            'site_name' => 'Secret Santa',                     //Name of the gift exchange app
            'admin_users' => serialize(array()),               //user emails who have admin privileges
            'domain_restriction' => serialize(array()),        //email login domain restriction regex
            'max_groups' => 5,                                 //Max groups user can be in
            'evt_partner_date' => serialize(array(12,25)),     //Partner assignment date [month,day]
            'evt_gift_day' => serialize(array(12,25)),         //Gift exchange date [month,date]
            'first_year' => intval(date('Y')),                 //first year that data exists
            'setup' => TRUE                                    //whether setup script has been run
        );

        $this->db->insert('globalvars', $global_vars);
    }

    public function down()
    {
        $this->dbforge->drop_table('blog');
    }
}