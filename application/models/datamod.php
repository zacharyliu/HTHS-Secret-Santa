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

    ////////////////////////////
    //KEY FUNCTIONS
    ////////////////////////////////
    /**
     * get user id based on name and email
     * @todo email is enough to determine condition of uniqueness
     * @param string $name     name to check
     * @param string $email    email to check
     * @return int             user id
     */
    public function getUserId($name, $email)
    {
        $this->db->where(array('name' => $name, 'email' => $email));
        $query = $this->db->get('users');
        $row = $query->row();
        return $row->id;
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
     * @param int       $id     id to store keys for
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
    public function checkGroup($code)
    { //checks if the group code exists
        $this->db->where(array('code' => $code, 'year' =>$this->current_year));
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
    public function checkGroupName($name)
    { //checks to see if the group with $name already exists
        $this->db->where(array('name' => $name, 'year' => $this->current_year));
        $query = $this->db->get('groups');
        if ($query->num_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * checks whether a user is already in a group
     * @todo id implementation
     * @param string $person        person to check
     * @param string $code          group code to check
     * @return bool                 true on success, false on failure
     */
    public function inGroup($person, $code)
    { //checks if a person is already in group
        $this->db->select('groups')->where('name', $person);
        $query = $this->db->get('users');
        $row = $query->row();
        $groups = $row->groups;
        if ($groups != "") {
            $groups = explode(",", $groups);
            if (in_array($code, $groups))
                return true;
            else return false;
        } else return false;
    }

    /**
     * count the number of members in a group
     * @todo simplified implementation
     * @param string $code      group code
     * @return int              number of members
     */
    public function countMembers($code)
    { //returns the number of members in a group based on code
        $members = $this->getMembers($code);
        if ($members)
            return count($members);
        else return 0;
    }

    /**
     * count number of groups user is in
     * @todo id implementation
     * @param string $person    person to check against
     * @return int              number of groups
     */
    public function countPersonGroups($person)
    { //returns the number of groups a person is currently a part of
        $groups = $this->getPersonGroups($person);
        if ($groups)
            return count($groups);
        else return 0;
    }

    /**
     * returns whether a group is deleteable
     * @todo i think this is private
     * @param $code     group code
     * @return bool
     */
    public function deleteable($code)
    { //returns if a group is deleteable or 'special'
        $this->db->select('deleteable')->where('code', $code);
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
    public function leaveable($code)
    { //returns if a group is leaveable (combine, redundant)
        $this->db->select('leaveable')->where('code', $code);
        $query = $this->db->get('groups');
        $row = $query->row();
        $leave = $row->leaveable;
        if ($leave)
            return true;
        else
            return false;
    }

    ///////////////////////////////////////
    //GROUP FUNCTIONS - Info Retrieval
    /////////////////////////////////////
    /**
     * get the name of a group by code
     * @param $code             group code
     * @return string|bool      false on failure
     */
    public function getGroupName($code)
    { //gets a group name from code
        $this->db->select('name')->where(array('code' => $code, 'year' =>$this->current_year));
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
    public function getGroupCode($name)
    { //gets a group code from name
        $this->db->select('code')->where(array('name' => $name, 'year' => $this->current_year));
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
    public function getGroupDescription($code)
    { //gets description of the group by code
        $this->db->select('description')->where(array('code' => $code, 'year' =>$this->current_year));
        $query = $this->db->get('groups');
        $row = $query->row();
        return $row->description;
    }

    /**
     * get an array of members that belong to a group
     * @todo id implementation
     * @param $code
     * @return array|bool
     */
    public function getMembers($code)
    { //get array of members who belong to a group
        $this->db->select('members')->where(array('code' => $code, 'year' => $this->current_year));
        $query = $this->db->get('groups');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $members = $row->members;
            if ($members != "")
                return explode(",", $members);
            else
                return false;
        }
        else return false;
    }

    /**
     * get an array of groups a person belongs to
     * @todo id implementation
     * @param string $person           user's name
     * @return string[]|bool           false on failure
     */
    public function getPersonGroups($person)
    { //get array of groups a person belongs to (input: persons name)
        $this->db->select('groups')->where(array('name' => $person, 'year' =>$this->current_year));
        $query = $this->db->get('users');
        $row = $query->row();
        $groups = $row->groups;
        if ($groups != "")
            return explode(",", $groups);
        else
            return false;
    }

    /**
     * get a person's partner for a group(assuming person is "give")
     * @todo id implementation
     * @param $code         group code
     * @param $person       person's name
     * @return string       person's partner
     */
    public function getPair($code, $person)
    { //get a person's partner for a group
        $this->db->select('receive');
        $query = $this->db->get_where('pairs', array('code' => $code, 'give' => $person, 'year' => $this->current_year));
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $receive = $row->receive;
            return $receive;
        } else return '[pending]';
    }

    ///////////////////////////////////////
    //GROUP FUNCTIONS - Group  Creation
    /////////////////////////////////////
    /**
     * generates a group code and pushes it to the addgroup function
     * @todo let addGroup handle gengroup
     * @todo id implementation
     * @param string $person        user's name
     * @param string $name          group name
     */
    public function genGroup($person, $name)
    {
        $code = $this->randstring(4);//generate a unique code
        $this->db->where('code', $code);
        $query = $this->db->get('groups');
        while ($query->num_rows() > 0) {//while the code is not unique
            $code = $this->randstring(4);
            $this->db->where('code', $code);
            $query = $this->db->get('groups');
        }
        $this->addGroup($person, $code, $name);
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
     * //add a new group to the master record OR add a person to the group
     * @todo id implementation
     * @param string $person        person's name
     * @param string $code          group code
     * @param null $name            group name
     * @return void
     */
    public function addGroup($person, $code, $name = null)
    {
        //if the group doesnt exist in master table, add it
        if (!$this->checkGroup($code))
            $this->db->insert('groups', array('code' => $code, 'name' => $name, 'members' => $person, 'year' =>$this->current_year));
        else {
            //update new member to list under group master table
            $members = $this->getMembers($code);
            $members[] = $person; //add person to array
            $members = implode(',', $members);
            $this->db->where(array('code' => $code, 'year' => $this->current_year))->update('groups', array('members' => $members));
        }
        //update person's group list
        if ($this->getPersonGroups($person) == false) //if the string is still empty
        $groups = array($code);
        else {
            $groups = $this->getPersonGroups($person);
            $groups[] = $code; //add person to array
        }
        $groups = implode(',', $groups);
        $this->db->select('groups')->where('name', $person);
        $this->db->update('users', array('groups' => $groups));
        $query = $this->db->get('users'); //clear previous select query to prevent future errors
    }

    /**
     * removes a person from a group based on group code
     * @todo id implementation
     * @param string $person        name of user
     * @param string $code          group code
     * @return bool
     */
    public function removeFromGroup($person, $code)
    {
        //check if the group is leaveable
        if ($this->leaveable($code)) {

            //remove membership on person's group list
            $groups = $this->getPersonGroups($person); //get list of groups
            $index = array_search($code, $groups);
            if ($index !== false) {
                unset($groups[$index]); //rm
                $groups = array_values($groups); //fix the index
                //send stuff to the database
                $groups = implode(',', $groups);
                $this->db->where('name', $person)->update('users', array('groups' => $groups));

                //remove membership on master groups table
                $members = $this->getMembers($code);
                $index = array_search($person, $members);
                if ($index !== false) {
                    unset($members[$index]); //rm
                    if (!empty($members))
                        $members = array_values($members); //fix the index
                    //send stuff to the database
                    $members = implode(',', $members);
                    $this->db->where(array('code' => $code, 'year' => $this->current_year))->update('groups', array('members' => $members));
                } else return false;
                //delete group if empty
                $this->deleteGroup($code);
                return true;
            } else return false;
        } else return false;
    }

    /**
     * deletes a group from the master group table based on code
     * @todo i think this is private
     * @param $code     group code
     * @return void
     */
    public function deleteGroup($code)
    {
        if ($this->checkGroup($code) && $this->deleteable($code)) {
            //check if the group is empty and not a special group
            $this->db->select('members')->where(array('code' => $code, 'year' =>$this->current_year));
            $query = $this->db->get('groups');
            $row = $query->row();
            $members = $row->members;
            if ($members == '')
                $this->db->where('code', $code)->delete('groups');
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
    public function listYearGroups($year = null){
        if ($year== NULL)
            $year = $this->current_year;//@todo is there a better way of doing this?
        $this->db->from('groups')->where('year',$year);
        return $this->db->get()->result();
    }

    /**
     * checks if pairing was already run for a group
     * @param $code         group code
     * @return bool
     */
    public function paired($code)
    {
        $this->db->from('pairs');
        $query = $this->db->where(array('code' => $code, 'year' =>$this->current_year));
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
    public function groupInfo($code)
    {
        return $this->db->from('groups')->where(array('code' => $code, $this->current_year))->get()->result();
    }

    /**
     * gets multiple rows in the table groups
     * @param $codeArray
     * @return array
     */
    public function groupInfoMultiple($codeArray)
    {
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

    ////SETTINGS
}