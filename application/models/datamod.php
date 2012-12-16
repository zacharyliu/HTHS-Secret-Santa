<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datamod extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	/////////////////////////
	//USER FUNCTIONS
	/////////////////////////
    public function addUser($name, $email) {
        $data = array('name' => $name, 'email' => $email);
        // Check if the user is already in the database
        $this->db->where($data);
        $query = $this->db->get('users');
        if ($query->num_rows() == 0) {
            // Not yet in the database, insert them
            $this->db->insert('users', $data);
            return true;
        } else {
            return false;
        }
    }
	
	public function countUsers() { //returns the number of registered users
		$query = $this->db->get('users');
		return $query->num_rows();
	}
	
    ////////////////////////////
	//KEY FUNCTIONS
	////////////////////////////////
    public function getUserId($name, $email) {
        $data = array('name' => $name, 'email' => $email);
        $this->db->where($data);
        $query = $this->db->get('users');
        $row = $query->row();
        return $row->id;
    }
    
	public function getPrivKey($id) {
		$this->db->select('privkey');
		$this->db->where('id',$id);
		$query = $this->db->get('users');
        $row = $query->row();
		$privkey = (isset($row->privkey) ? $row->privkey : '');
        if ($privkey != '')
			return $privkey;
		else
			return false;
	}
	
	public function getPubKey($id) {
		$this->db->select('pubkey')->where('id',$id);
		$query = $this->db->get('users');
        $row = $query->row();
		$pubkey = (isset($row->pubkey) ? $row->pubkey : '');
        if ($pubkey != "")
			return $pubkey;
		else
			return false;
	}
	
	public function storeKeyPair($id, $keys) {
	    $data = array('privkey' => $keys[0], 'pubkey' => $keys[1]);
        // Check if privkey is in db
		if ($this->getPrivKey($id) == false) {
			$this->db->where('id',$id)->update('users', $data);
		}
		else return false;
	}

	///////////////////////////////////////
	//GROUP FUNCTIONS
	/////////////////////////////////////
	public function checkGroup($code) { //checks if the group code exists
		$this->db->where('code',$code);
		$query = $this->db->get('groups');
		if ($query->num_rows() > 0)
			return true;
		else
			return false;
    }
	
	public function checkGroupName($name) { //checks to see if the group with $name already exists
		$this->db->where('name',$name);
		$query = $this->db->get('groups');
		if ($query->num_rows() > 0)
			return true;
		else
			return false;
	}
	
	public function getGroupName($code) { //gets a group name from code
		$this->db->select('name')->where('code',$code);
		$query = $this->db->get('groups');
        $row = $query->row();
		$name= $row->name;
		//echo $this->db->last_query();
		if ($name != '')
			return $name;
		else return false;
	}
	
	public function getGroupCode($name) { //gets a group code from name
		$this->db->select('code')->where('name',$name);
		$query = $this->db->get('groups');
        $row = $query->row();
		$code = $row->code;
		//echo $this->db->last_query();
		if ($code != '')
			return $code;
		else return false;
	}
	
	public function getGroupDescription($code) { //gets description of the group by code
		$this->db->select('description')->where('code',$code);
		$query = $this->db->get('groups');
        $row = $query->row();
		return $row->description;
		}

	public function inGroup($person, $code) { //checks if a person is already in group
		$this->db->select('groups')->where('name',$person);
		$query = $this->db->get('users');
        $row = $query->row();
		$groups = $row->groups;
        if ($groups != "") {
			$groups = explode(",",$groups);
			if (in_array($code,$groups))
				return true;
			else return false;
		}
		else return false;
    }
	public function genGroup($person, $name) { //generates a group code and pushes it to the addgroup function
		$code = $this->randstring(4);
		$this->db->where('code',$code);
		$query = $this->db->get('groups');
		while ($query->num_rows() > 0) {
			$code = $this->randstring(4);
			$this->db->where('code',$str);
			$query = $this->db->get('groups');
		}
		$this->addGroup($person, $code, $name);
	}
	
	public function randstring($len, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789') {
	    $str = '';
		$count = strlen($charset);
		while ($len--)
			$str .= $charset[mt_rand(0, $count-1)];
		return $str;
	}
	
	public function getMembers($code) {//get array of members who belong to a group
		$this->db->select('members')->where('code',$code);
		$query = $this->db->get('groups');
        $row = $query->row();
		$members = $row->members;
        if ($members != "")
			return explode(",",$members);
		else
			return false;
	}
	public function getPersonGroups($person) {//get array of groups a person belongs to (input: persons name)
		$this->db->select('groups')->where('name',$person);
		$query = $this->db->get('users');
        $row = $query->row();
		$groups = $row->groups;
        if ($groups != "")
			return explode(",",$groups);
		else
			return false;
	}
	
	public function getPair($code,$person) { //get a person's partner for a group
		$this->db->select('receive');
		$query = $this->db->get_where('pairs',array('group' => $code, 'give' => $person));
		if ($query->num_rows() > 0) {
			$row = $query->row();
			$receive = $row->receive;
			return $receive;
		}
		else return '[pending]';
	}
	
	public function countMembers($code) {//returns the number of members in a group based on code
		$members = $this->getMembers($code);
		if ($members)
			return count($members);
		else return 0;
	}
	
	public function countPersonGroups($person) { //returns the number of groups a person is currently a part of
		$groups = $this->getPersonGroups($person);
		if ($groups)
			return count($groups);
		else return 0;
	}
	
	public function deleteable($code) {//returns if a group is deleteable or 'special'
		$this->db->select('deleteable')->where('code',$code);
		$query = $this->db->get('groups');
        $row = $query->row();
		$delete = $row->deleteable;
        if ($delete)
			return true;
		else
			return false;
	}
	
	public function leaveable($code) {//returns if a group is leaveable (combine, redundant)
		$this->db->select('leaveable')->where('code',$code);
		$query = $this->db->get('groups');
        $row = $query->row();
		$leave = $row->leaveable;
        if ($leave)
			return true;
		else
			return false;
	}
			
	public function addGroup($person, $code, $name = null) { //add a new group to the master record OR add a person to the group
		//if the group doesnt exist in master table, add it
		if (!$this->checkGroup($code))
			$this->db->insert('groups', array('code'=>$code, 'name'=>$name, 'members' =>$person));
		else {
		//update new member to list under group master table
		$members=$this->getMembers($code);
		$members[]=$person; //add person to array
		$members = implode(',',$members);
		$this->db->where('code',$code)->update('groups', array('members'=>$members));
		}
		//update person's group list
		if ($this->getPersonGroups($person) == false) //if the string is still empty
			$groups = array($code);
		else {
			$groups=$this->getPersonGroups($person);
			$groups[]=$code; //add person to array
		}
		$groups = implode(',',$groups);
		$this->db->select('groups')->where('name',$person);
        $this->db->update('users', array('groups'=>$groups));
		$query = $this->db->get('users');//clear select query
	}
	
	public function removeFromGroup($person,$code) { //removes a person from a group based on group code
		//check if the group is leaveable
		if ($this->leaveable($code)) {
			
			//remove membership on person's group list
			$groups = $this->getPersonGroups($person); //get list of groups
			$index = array_search($code,$groups);
			if ($index !== false){
				unset($groups[$index]);//rm
				$groups= array_values($groups); //fix the index
				//send stuff to the database
				$groups = implode(',',$groups);
				$this->db->where('name',$person)->update('users', array('groups'=>$groups));
				
				//remove membership on master groups table
				$members = $this->getMembers($code);
				$index = array_search($person,$members);
				if ($index !== false){
					unset($members[$index]);//rm
					if(!empty($members))
						$members = array_values($members); //fix the index
					//send stuff to the database
					$members = implode(',',$members);
					$this->db->where('code',$code)->update('groups', array('members'=>$members));
				}
				else return false;
				//delete group if empty
				$this->deleteGroup($code);
				return true;
			}
			else return false;
		}
		else return false;
	}
	
	public function deleteGroup($code) { //deletes a group from the master group table based on code
		if ($this->checkGroup($code) && $this->deleteable($code)) {
		//check if the group is empty and not a special group
		$this->db->select('members')->where('code',$code);
		$query = $this->db->get('groups');
        $row = $query->row();
		$members = $row->members;
		if ($members == '')
			$this->db->where('code',$code)->delete('groups');
		}
	}
    
    public function listGroups() {
        $this->db->from('groups');
        return $this->db->get()->result();
    }
    
    public function paired($code) {
        $this->db->from('pairs');
        $query = $this->db->where('group', $code);
        if ($query->get()->num_rows() == 0) {
            return false;
        } else {
            return true;
        }
    }
    
    public function groupInfo($code) {
        return $this->db->from('groups')->where('code', $code)->get()->result();
    }
    
    public function groupInfoMultiple($codeArray) {
        foreach ($codeArray as $code) {
            $this->db->or_where('code', $code);
        }
        $groups = $this->db->from('groups')->get()->result();
        $output = array();
        foreach ($groups as $group) {
            $output[$group->code] = $group; 
        }
        return $output;
    }
}