<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Deprecated model functions
 * NOTE: Do not use this model in production.
 */
class Deprecated extends CI_Model
{
    /**
     * Model constructor
     * Prohibit use of model
     */
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        die("Use of Deprecated.php model prohibited."); //Prohibit use of model

    }

    /*
     * USER FUNCTIONS
     */

    /**
     * get a user's private key by id
     * @param int $id id to retrieve key for
     * @return bool|string      private key on success, false on failure
     * @deprecated
     */
    public function getPrivKey($id)
    {
        $this->db->select('privkey');
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        $row = $query->row();
        $privkey = (isset($row->privkey) ? $row->privkey : '');
        if ($privkey != '')
            return $privkey;
        else
            return false;
    }

    /**
     * get a user's public key by id
     * @param int $id id to retrieve key for
     * @return bool|string      public key on success, false on failure
     * @deprecated
     */
    public function getPubKey($id)
    {
        $this->db->select('pubkey')->where('id', $id);
        $query = $this->db->get('users');
        $row = $query->row();
        $pubkey = (isset($row->pubkey) ? $row->pubkey : '');
        if ($pubkey != "")
            return $pubkey;
        else
            return false;
    }

    /**
     * store a generated [private,public] key pair
     * @param int $id id to store keys for
     * @param string[2] $keys   keys to store
     * @return bool             true on success, false on failure
     * @deprecated
     */
    public function storeKeyPair($id, $keys)
    {
        $data = array('privkey' => $keys[0], 'pubkey' => $keys[1]);
        // Check if privkey is in db
        if ($this->getPrivKey($id) == false) {
            $this->db->where('id', $id)->update('users', $data);
            return true;
        } else return false;
    }

    /*
     * GROUP FUNCTIONS
     */

    /**
     * checks whether a group name already exists
     * @deprecated              group names no longer have to be unique
     * @todo potential security vulnerability - fishing for group names
     * @param string $name      group name
     * @param int $year         year to check (default: $this->current_year)
     * @return bool             true on success, false in failure
     */
    public function checkGroupName($name, $year = NULL)
    { //checks to see if the group with $name already exists
        if ($year == NULL) $year = $this->current_year;
        $this->db->where(array('name' => $name, 'year' => $year));
        $query = $this->db->get('groups');
        if ($query->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * get group description by code
     * @deprecated
     * @param $code             group code
     * @param int $year         year to check (default: $this->current_year)
     * @return string           group description
     */
    public function getGroupDescription($code, $year = NULL)
    { //gets description of the group by code
        if ($year == NULL) $year = $this->current_year;
        $this->db->select('description')->where(array('code' => $code, 'year' => $year));
        $query = $this->db->get('groups');
        $row = $query->row();
        return $row->description;
    }

    /**
     * get an array of groups a person belongs to
     * @deprecated
     * @param string $id        user id
     * @param int $year         year to check (default: $this->current_year)
     * @return string[]|bool    false on failure
     */
    public function getPersonGroups($id, $year = NULL)
    { //get array of groups a person belongs to (input: persons id)
        if ($year == NULL) $year = $this->current_year;
        $this->db->select('code')->where(array('id' => $id, 'year' => $year));
        $query = $this->db->get('users_groups');
        if ($query->num_rows() > 0) {
            $groups = array();
            foreach ($query->result() as $row) {
                $groups[] = $row->code;
            }
            return $groups;
        } else return false;
    }


    /*
     * ADMIN FUNCTIONS
     */

    /**
     * add all members to the HTHS global group
     * @deprecated
     */
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

    /**
     * @deprecated
     * lock groups from previous year:
     * sets leaveable = 0 in the groups table
     * advances current_year global variable to this year
     */
    public function lockold()
    {
        $this->db->where('year', getPrevYear())->update("groups", array("leaveable", 0));
    }

    /**
     * @deprecated
     * gets the previous year based on whether christmas has passed or not
     * Returns previous year if (current year) < x < (christmas)
     * Returns current year if (christmas) < x < (end of current year)
     */
    public function getPrevYear()
    {
        $month = intval(date('n'));
        $day = intval(date('j'));
        $year = intval(date('Y'));
        if ($month == 12)
            if ($day <= 25) //before or on christmas
                return $year - 1;
            else return $year;
        else return $year - 1;
    }

}
