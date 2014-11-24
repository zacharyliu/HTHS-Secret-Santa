<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setup extends CI_Migration
{

    public function up()
    {

        $global_vars = array(
            'site_name' => 'Secret Santa',                              //Name of the gift exchange app
            'admin_users' => serialize(array()),                        //user emails who have admin privileges
            'domain_restriction' => "",                                 //email login domain restriction regex
            'max_groups' => 5,                                          //Max groups user can be in
            'evt_partner_date' => serialize(array(12,15)),              //Partner assignment date [month,day]
            'evt_gift_date' => serialize(array(12,25)),                 //Gift exchange date [month,date]
            'first_year' => intval(date('Y')),                          //first year that data exists
        );

        foreach($global_vars as $key=>$value){
            $this->db->insert('globalvars', array("key" =>$key, "val" => $value));
        }
        //mark setup script as run
        $this->db->update('globalvars', array("val" => "true"), array('key'=>'setup'));
        //echo 'done';
    }

    public function down()
    {
    }
}