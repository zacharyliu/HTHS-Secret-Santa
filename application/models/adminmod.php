<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Adminmod extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    public function addGroupHTHS()
    { //postfix: make sure all members are in HTHS global group
        $sql = "SELECT `name` FROM `users`";
        $resultSet = $this->db->query($sql);
        $total = $resultSet->num_rows();
        for ($i = 0; $i < $total; $i++) {
            $row = $resultSet->row_array($i);
            if (!$this->datamod->inGroup($row['name'], 'hths')) //prevent duplicate additions to hths group
            $this->datamod->addgroup($row['name'], 'hths');
        }

    }

    public function pairCustom($code)
    {
        $this->db->from('pairs');
        $this->db->where('code', $code);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            if ($this->datamod->countMembers($code) >= 5) {
                $members = $this->datamod->getMembers($code);
                shuffle($members); //randomize array
                $total = count($members);
                //var_dump($members);
                for ($i = 0; $i < $total; $i++) {
                    $give = $members[$i];
                    $receive = ($i + 1 < $total ? $members[$i + 1] : $members[0]); //loop back to first element if i+1 > total # of members
                    $this->addPair($code, $give, $receive);
                }
                return $total;
            } else return false;
        } else return false;
    }

    public function addPair($code, $give, $receive)
    {
        $data = array('group' => $code, 'give' => $give, 'receive' => $receive);
        // Check if the pair is already in the database
        $this->db->where($data);
        $query = $this->db->get('pairs');
        if ($query->num_rows() == 0) {
            // Not yet in the database, insert them
            $this->db->insert('pairs', $data);
            return true;
        } else {
            return false;
        }
    }

    /**
     * lock groups from previous year:
     * sets leaveable = 0 in the groups table
     * advances current_year global variable to this year
     */
    public function lockold(){
        $this->db->where('year', getPrevYear())->update("groups",array("leaveable",0));
    }

    /**
     * gets the previous year based on whether christmas has passed or not
     * Returns previous year if (current year) < x < (christmas)
     * Returns current year if (christmas) < x < (end of current year)
     */
    public function getPrevYear() {
        $month = intval(date('n'));
        $day = intval(date('j'));
        $year = intval(date('Y'));
        if ($month == 12)
            if ($day <= 25) //before or on christmas
                return $year-1;
            else return $year;
        else return $year-1;
    }
}