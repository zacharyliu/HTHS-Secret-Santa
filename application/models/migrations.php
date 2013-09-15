<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Migrations from old database schema
 */


class Migrations extends CI_Model
{

    /**
     * model constructor
     */
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    /**
     * matches user name with user id
     */
    private function _lookupname($name){
        $query=$this->db->select('id')->where('name',$name)->get('users');
        $row=$query->row();
        return $row->id;
    }
    /**
     * migration year: 2013
     * migrates user groups from comma separated group codes in users table to user_groups table
     */
    public function migrateUserGroups()
    {
        $this->db->select(array('id', 'groups'));
        $query = $this->db->get('users');
        foreach ($query->result() as $row) {
            $id = $row->id;
            $groups = explode(",", $row->groups);
            foreach ($groups as $group) {
                $this->db->insert('users_groups',array("id" => $id, "code" => $group, 'year' =>'2012'));
            }
        }
        echo "Success!";
    }

    /**
     * migration year: 2013
     * migrates group members as comma separated names in group code to id,code,year in separate table
     */

    public function migrateGroupMembers(){
        $this->db->select(array('code','members'));
        $query = $this->db->get('groups');
        foreach ($query->result() as $row){
            $code = $row->code;
            $members = explode(',',$row->members);
            foreach ($members as $member) {
                if ($member !=null){
                $id = $this->_lookupname($member);
                $this->db->insert('groups_members',array('code'=>$code,'member'=>$id,"year"=>'2012'));
                }
            }
        }
        echo "Success!";
    }
}