<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datamod extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

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
    
    public function getUserId($name, $email) {
        $data = array('name' => $name, 'email' => $email);
        $this->db->where($data);
        $query = $this->db->get('users');
        $row = $query->row();
        return $row->id;
    }
    
	public function getPrivKey($id) {
		$this->db->where('id',$id);
		$query = $this->db->get('users');
        $row = $query->row();
		$privkey = $row->privkey;
        if ($privkey)
			return $privkey;
		else
			return false;
	}
	
	public function getPubKey($id) {
		$this->db->where('id',$id);
		$query = $this->db->get('users');
        $row = $query->row();
		$privkey = $row->pubkey;
        if ($pubkey)
			return $pubkey;
		else
			return false;
	}
	
	public function storeKeyPair($id, $keys) {
	    $data = array('privkey' => $keys[0], 'pubkey' => $keys[1]);
        // Check if privkey is in db
		if ($this->getPrivKey($id) == false) {
			$this->db->where('id',$id);
            $this->db->update('users', $data);
		}
		else return false;
	}
	
}