<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminmod extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function addGroupHTHS() {//postfix: make sure all members are in HTHS global group
		$sql = "SELECT `name` FROM `users`";
		$resultSet = $this->db->query($sql);
		$total = $resultSet->num_rows();
		for($i=0;$i<$total;$i++) {
			$row = $resultSet->row_array($i);
			if (!$this->datamod->inGroup($row['name'], 'hths')) //prevent duplicate additions to hths group
			$this->datamod->addgroup($row['name'],'hths');
		}
		
	}
}