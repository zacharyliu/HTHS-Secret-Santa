<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Datamod extends CI_Model
{

    /*Class Variables*/
    var $current_year; //declare current year class variable

    /**
     * model constructor
     * sets $current_year
     */
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->current_year = intval(date('Y')); //set the current year for group operations
    }

    /////////////////////////
    //USER FUNCTIONS
    /////////////////////////
    /**
     * Add a new user based on name and email
     *
     * @param string $name     name to insert
     * @param string $email    email to insert
     * @return bool            true on success, false on failure
     */
    public function addUser($name, $email)
    {
        // Check if the user is already in the database
        $this->db->where(array('name' => $name, 'email' => $email));
        $query = $this->db->get('users');
        if ($query->num_rows() == 0) {
            // Not yet in the database, insert them
            $data = array('name' => $name, 'email' => $email, 'year_join' => $this->current_year);
            $this->db->insert('users', $data);
            return true;
        } else {
            return false;
        }
    }

    /**
     * counts the current number of registered users
     * @return int          number of users
     */
    public function countUsers()
    { //returns the number of registered users
        $query = $this->db->get('users');
        return $query->num_rows();
    }

    public function totalgiftsExchanged() {
        return $this->db->get('pairs')->num_rows();
    }
    /**
     * get the user's join year based on user id
     * @return int;
     */
    public function getJoinYear($id)
    {
        $query = $this->db->select('year_join')->where('id', $id)->get('users');
        $row = $query->row();
        return $row->year_join;
    }

    ////////////////////////////
    //KEY FUNCTIONS
    ////////////////////////////////
    /**
     * get user id based on email
     * @param string $email    email to check
     * @return int             user id
     */
    public function getUserId($email)
    {
        $this->db->select('id')->where(array('email' => $email));
        $query = $this->db->get('users');
        $row = $query->row();
        return $row->id;
    }

    /**
     * get user name based on id
     * @param int $id           id to check
     * @return string $name     user name
     * @todo combine with getUserId
     */
    public function getUserName($id)
    {
        $this->db->select('name')->where(array('id' => $id));
        $query = $this->db->get('users');
        $row = $query->row();
        return $row->name;
    }

    /**
     * get a user's private key by id
     * @param int $id           id to retrieve key for
     * @return bool|string      private key on success, false on failure
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
     * @param int $id           id to retrieve key for
     * @return bool|string      public key on success, false on failure
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
     * @param int $id     id to store keys for
     * @param string[2] $keys   keys to store
     * @return bool             true on success, false on failure
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

    ///////////////////////////////////////
    //GROUP FUNCTIONS - Counting and Checking
    /////////////////////////////////////
    /**
     * checks whether a group code already exists
     * @param int $code     code to check against
     * @return bool         true on success, false on failure
     */
    public function checkGroup($code, $year = NULL)
    { //checks if the group code exists
        if ($year == NULL) $year = $this->current_year;
        $this->db->where(array('code' => $code, 'year' => $year));
        $query = $this->db->get('groups');
        if ($query->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * checks whether a group name already exists
     * @todo potential security vulnerability - fishing for group names
     * @param string $name      group name to check against
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
     * checks whether a user is already in a group
     * @param string $person        person to check
     * @param string $code          group code to check
     * @return bool                 true on success, false on failure
     */
    public function inGroup($id, $code, $year = NULL)
    { //checks if a person is already in group
        if ($year == NULL) $year = $this->current_year;
        $this->db->select('code')->where(array('id' => $id, 'code' => $code, 'year' => $year));
        $query = $this->db->get('users_groups');
        if ($query->num_rows() > 0)
            return true;
        else return false;
    }

    /**
     * count the number of members in a group
     * @todo simplified implementation
     * @param string $code      group code
     * @return int              number of members
     */
    public function countMembers($code, $year = NULL)
    { //returns the number of members in a group based on code
        if ($year == NULL) $year = $this->current_year;
        $this->db->select("id")->where(array("code" => $code, 'year' => $year));
        $query = $this->db->get('users_groups');
        return $query->num_rows();
    }

    /**
     * count number of groups user is in
     * @todo simplified implementation
     * @param string $person    person to check against
     * @return int              number of groups
     */
    public function countPersonGroups($id, $year = NULL)
    { //returns the number of groups a person is currently a part of
        if ($year == NULL) $year = $this->current_year;
        $this->db->where(array("id" => $id, 'year' => $year));
        //$query = $this->db->get('users_groups');
        return $this->db->count_all_results('users_groups');
    }

    /**
     * returns whether a group is deleteable
     * @todo i think this is private
     * @param $code     group code
     * @return bool
     */
    public function deleteable($code, $year = NULL)
    { //returns if a group is deleteable or 'special'
        if ($year == NULL) $year = $this->current_year;
        $this->db->select('deleteable')->where(array('code' => $code, 'year' => $year));
        $query = $this->db->get('groups');
        $row = $query->row();
        $delete = $row->deleteable;
        if ($delete)
            return true;
        else
            return false;
    }

    /**
     * returns whether a group is leaveable
     * @todo might be private
     * @todo combine with above?
     * @param $code         group code
     * @return bool
     */
    public function leaveable($code, $year = NULL)
    { //returns if a group is leaveable (combine, redundant)
        if ($year == NULL) $year = $this->current_year;
        $this->db->select('leaveable')->where(array('code' => $code, 'year' => $year));
        $query = $this->db->get('groups');
        $row = $query->row();
        if ($row->leaveable)
            return true;
        else return false;
    }

    ///////////////////////////////////////
    //GROUP FUNCTIONS - Info Retrieval
    /////////////////////////////////////
    /**
     * get the name of a group by code
     * @param $code             group code
     * @return string|bool      false on failure
     */
    public function getGroupName($code, $year = NULL)
    { //gets a group name from code
        if ($year == NULL) $year = $this->current_year;
        $this->db->select('name')->where(array('code' => $code, 'year' => $year));
        $query = $this->db->get('groups');
        $row = $query->row();
        $name = $row->name;
        //echo $this->db->last_query();
        if ($name != '')
            return $name;
        else return false;
    }

    /**
     * get group code by group name
     * @param string $name      group name
     * @return bool             false on failure
     */
    public function getGroupCode($name, $year = NULL)
    { //gets a group code from name
        if ($year == NULL) $year = $this->current_year;
        $this->db->select('code')->where(array('name' => $name, 'year' => $year));
        $query = $this->db->get('groups');
        $row = $query->row();
        $code = $row->code;
        //echo $this->db->last_query();
        if ($code != '')
            return $code;
        else return false;
    }

    /**
     * get group description by code
     * @param $code     group code
     * @return string   group description
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
     * get an array of members that belong to a group
     * @param $code
     * @return array|bool
     */
    public function getMembers($code, $year = NULL)
    { //get array of members who belong to a group
        if ($year == NULL) $year = $this->current_year;
        $this->db->select('id')->where(array('code' => $code, 'year' => $year));
        $query = $this->db->get('users_groups');
        if ($query->num_rows() > 0) {
            $members = array();
            foreach ($query->result() as $row) {
                $members[] = $row->id;
            }
            return $members;
        } else return false;
    }

    /**
     * get an array of groups a person belongs to
     * @param string $person           user's id
     * @return string[]|bool           false on failure
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

    /**
     * get a person's partner for a group(assuming person is "give")
     * @param $code         group code
     * @param $person       person's name
     * @return string       person's partner
     */
    public function getPair($code, $id, $year = NULL)
    { //get a person's partner for a group
        if ($year == NULL) $year = $this->current_year;
        $this->db->select('receive');
        $query = $this->db->get_where('pairs', array('code' => $code, 'give' => $id, 'year' => $year));
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $this->getUserName($row->receive);
        } else return '[pending]';
    }

    ///////////////////////////////////////
    //GROUP FUNCTIONS - Group  Creation
    /////////////////////////////////////
    /**
     * generates a group code and pushes it to the addgroup function
     * @todo let addGroup handle gengroup
     * @param string $person        user's name
     * @param string $name          group name
     */
    public function genGroup($id, $name)
    {
        $code = $this->randstring(4); //generate a unique code
        $this->db->where('code', $code);
        $query = $this->db->get('groups');
        while ($query->num_rows() > 0) { //while the code is not unique
            $code = $this->randstring(4);
            $this->db->where('code', $code);
            $query = $this->db->get('groups');
        }
        $this->addGroup($id, $code, $name);
    }

    /**
     * generates a random string of length $len
     * @param int $len              length of string
     * @param string $charset       charset of string
     * @return string               random string
     */
    private function randstring($len, $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
    {
        $str = '';
        $count = strlen($charset);
        while ($len--)
            $str .= $charset[mt_rand(0, $count - 1)];
        return $str;
    }

    /**
     * add a new group to the master record OR add a person to the group
     * @param string $person        person's name
     * @param string $code          group code
     * @param null $name            group name
     * @return void
     */
    public function addGroup($id, $code, $name = null)
    {
        //if the group doesnt exist in master table, add it
        if (!$this->checkGroup($code)){
            $this->db->insert('groups', array('code' => $code, 'name' => $name, 'year' => $this->current_year));
        //add a new membership entry as well
        }
            $this->db->insert('users_groups', array('id' => $id, 'code' => $code, 'year' => $this->current_year));
    }

    /**
     * removes a person from a group based on group code
     * @param string $person        name of user
     * @param string $code          group code
     * @return bool
     */
    public function removeFromGroup($id, $code, $year = NULL)
    {
        if ($year == NULL) $year = $this->current_year;
        //check if the group is leaveable
        if ($this->leaveable($code)) {
            $this->db->delete('users_groups', array('id' => $id, 'code' => $code, 'year' => $year));
            if ($this->countMembers($code, $year) == 0)
                $this->deleteGroup($code, $year);
            return true;
        } else return false;
    }

    /**
     * deletes a group from the master group table based on code
     * @todo i think this is private
     * @param $code     group code
     * @return void
     */
    public function deleteGroup($code, $year = NULL)
    {
        if ($year == NULL) $year = $this->current_year;
        if ($this->checkGroup($code) && $this->deleteable($code)) {
            //check if the group is empty and not a special group
            if ($this->countMembers($code, $year) == 0)
                $this->db->delete('groups', array('code' => $code, 'year' => $year));
        }
    }

    /**
     * returns all groups
     * @deprecated
     * @return mixed
     */
    public function listAllGroups()
    { //returns all groups
        $this->db->from('groups');
        return $this->db->get()->result();
    }

    /**
     * returns groups based on inputted year
     */
    public function listYearGroups($year = null)
    {
        if ($year == NULL) $year = $this->current_year; //@todo is there a better way of doing this?
        $this->db->from('groups')->where('year', $year);
        return $this->db->get()->result();
    }

    /**
     * checks if pairing was already run for a group
     * @param $code         group code
     * @return bool
     */
    public function paired($code, $year = NULL)
    {
        if ($year == NULL) $year = $this->current_year;
        $this->db->from('pairs');
        $query = $this->db->where(array('code' => $code, 'year' => $year));
        if ($query->get()->num_rows() == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * gets a row in the table groups
     * @param $code
     * @return mixed
     */
    public function groupInfo($code, $year = NULL)
    {
        if ($year == NULL) $year = $this->current_year;
        return $this->db->from('groups')->where(array('code' => $code, $year))->get()->result();
    }

    /**
     * gets multiple rows in the table groups
     * @param $codeArray
     * @return array
     */
    public function groupInfoMultiple($codeArray, $year = NULL)
    {
        if ($year == NULL) $year = $this->current_year;
        if ($codeArray != false){//prevent empty array exception
            foreach ($codeArray as $code) {
                $this->db->or_where('code', $code);
                $this->db->where('year', $year);
            }
            $query = $this->db->from('groups')->get();
            $output = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $group) {
                    $output[] = $group;
                }
            }
            return $output;
    }
        else return array();
    }

    ////SETTINGS

    ////PROFILE RETRIEVAL
    /**
     * get year user joined
     * @param $id
     * @return mixed
     */
    public function userStats($id) {
        $this->db->select(array('year_join','class'))->where(array('id' => $id));
        $query = $this->db->get('users');
        $row = $query->row();
        return $row;
    }


    public function giftsExchanged($id) {
        $query = $this->db->select('give')->where(array('give' => $id))->get("pairs");
        return $query->num_rows();
    }

    ///DISCOVER GROUPS
    public function listTrendingGroups(){
        $data = false;
        foreach ($this->db->order_by("name","asc")->get_where('groups',array("private"=>0),10)->result() as $row){
            $data[] = $row;
        }
        return $data;
    }
}