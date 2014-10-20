<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Datamod extends CI_Model
{

    /**
     * @var int $current_year       current calendar year
     */
    var $current_year;

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

    /**
     * Gets global vars based on string or array of keys
     * val will be unserialized if serialized
     * @param null|string|array $keys       null will retrieve all global vars
     * @return array|bool                   array of vars in [key,value] or false if not found
     */
    public function getGlobalVar($keys = NULL){
        $this->db->select('*');
        if ($keys != NULL) { //no key specified
            if (is_array($keys)){   //array of keys
                foreach($keys as $key) {
                    $this->db->or_where('key',$key); //string of key
                }
            }
            else $this->db->where('key',$keys);
        }
        $query = $this->db->get('globalvars');
        $output = array();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $var) {
                if ($this->__isSerialized($var->val)){ //unserialize serialized data
                    $var->val = unserialize($var->val);
                }
                $output[$var->key] = $var->val; //normalize output
            }
            return $output;
        }
        return false;

    }

    /**
     * check if a string is a serialized object or array
     * @param $str          string to check
     * @return bool         true on serialized
     * @private
     */
    private function __isSerialized($str) {
        $data = @unserialize($str);
        if ($str === 'b:0;' || $data !== false) {
            return true;
        } else {
            return false;
        }
    }

    /////////////////////////
    //USER FUNCTIONS
    /////////////////////////
    /**
     * Add a new user based on name and email
     *
     * @param string $name name to insert
     * @param string $email email to insert
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

    /**
     * Counts the number of users who have joined a group in the given year
     * @param null $year group year to filter by (default: current year)
     * @return int number of users
     */
    public function countUsersYear($year = null)
    {
        if ($year == null) $year = $this->current_year;
        $this->db->distinct()
            ->select('users.name')
            ->from('users')
            ->join('users_groups', 'users.id = users_groups.id', 'inner')
            ->where('users_groups.year', $year);
        return $this->db->get()->num_rows();
    }

    /**
     * get total gifts exchanged by counting paired members
     * @param boolean $include      whether to include the current year
     * @return mixed
     */
    public function totalgiftsExchanged($include = false)
    {
        if ($include)
            return $this->db->get('pairs')->num_rows();
        else return $this->db->where("year < ".$this->current_year)->get('pairs')->num_rows();
    }

    /**
     * get the user's join year based on user id
     * @param int $id    user id
     * @return int
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
     * @param string $email email to check
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
     * @param int $id id to check
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
     * check whether email is whitelisted for registration
     * @param string $email         email to check
     * @return bool                 true on success, false on failure
     */
    public function checkAllowedEmailException($email){
        $query = $this->db->select("email")->where(array('email'=>$email))->get('allowed_emails');

        if ($query->num_rows() >0) {
            return true;
        } else return false;
    }



    ///////////////////////////////////////////
    //GROUP FUNCTIONS - Counting and Checking//
    ///////////////////////////////////////////

    /**
     * checks whether a group code already exists
     * @param int $code     code to check against
     * @param int $year     year to check (default: $this->current_year)
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
     * checks whether a user is already in a group
     * @param int $id               user id
     * @param string $code          group code
     * @param int $year             year to check (default: $this->current_year)
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
     * @param int $year         year to check (default: $this->current_year)
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
     * @param int $id           user id
     * @param int $year         year to check (default: $this->current_year)
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
     * returns whether a group is deleteable (i.e. has the delteable flag set)
     * @param $code                 group code
     * @param int $year             year to check (default: $this->current_year)
     * @return bool
     * @private
     */
    private function deleteable($code, $year = NULL)
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
     * @todo combine with above?
     * @param $code                 group code
     * @param int $year             year to check (default: $this->current_year)
     * @return bool
     * @private
     */
    private function leaveable($code, $year = NULL)
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
     * @param $code                 group code
     * @param int $year             year to check (default: $this->current_year)
     * @return string|bool          false on failure
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
     * @deprecated
     * @param string $name      group name
     * @param int $year         year to check (default: $this->current_year)
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
     * get an array of members that belong to a group
     * @param int $code         group code
     * @param int $year         year to check (default: $this->current_year)
     * @return array|bool       false on group does not exist or no members
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
     * Get array of member names in a group
     * TODO: make general function to combine with getMembers() function
     * TODO: move into separate groupmod model
     * @param int $code         group code
     * @param int $year         year to check (default: $this->current_year)
     * @return array
     */
    public function getMemberNames($code, $year = NULL)
    {
        if ($year == NULL) $year = $this->current_year;
        $this->db->select('users.name');
        $this->db->where(array('users_groups.code' => $code, 'users_groups.year' => $year));
        $this->db->from('users_groups');
        $this->db->join('users', 'users_groups.id = users.id', 'left');
        $memberNames = array();
        foreach ($this->db->get()->result() as $member) {
            array_push($memberNames, $member->name);
        }
        return $memberNames;
    }

    /**
     * get a user's partner for a group(assuming user is "give")
     * @param $code         group code
     * @param $id           user id
     * @param int $year     year to check (default: $this->current_year)
     * @return string       user's partner ('pending' unpaired)
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

    /**
     * get user id of owner of group
     * @param $code             group code
     * @param null $year        year to check(default: $this->current_year)
     * @return null|int         null if no owner
     */
    public function getGroupOwner($code, $year = NULL)
    {
        if ($year == NULL) $year = $this->current_year;
        $this->db->select('owner')->where(array('code' => $code, 'year' => $year));
        $query = $this->db->get('groups_owner');
        if ($query->num_rows() == 1) {
            $row = $query->row();
            return $row->owner;
        } else return null;
    }

    ///////////////////////////////////////
    //GROUP FUNCTIONS - Group  Creation
    /////////////////////////////////////
    /**
     * generates a group code and checks that it is unique
     * @return string       code
     * @private
     */

    private function __genGroup()
    {
        $code = $this->__randstring(4); //generate a unique code
        $this->db->where('code', $code);
        $query = $this->db->get('groups');
        while ($query->num_rows() > 0) { //while the code is not unique
            $code = $this->__randstring(4);
            $this->db->where('code', $code);
            $query = $this->db->get('groups');
        }
        return $code;
    }

    /**
     * generates a random string of length $len
     * @param int $len length of string
     * @param string $charset charset of string
     * @return string               random string
     * @private
     */
    private function __randstring($len, $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
    {
        $str = '';
        $count = strlen($charset);
        while ($len--)
            $str .= $charset[mt_rand(0, $count - 1)];
        return $str;
    }

    /**
     * add a person to a group, and create a group if it does not exist
     * @param string $id                user id
     * @param string $name              group name
     * @param string $description       group description
     * @param string $code              group code
     * @return void
     */
    public function addGroup($id, $name = null, $description = null, $code = null)
    {
        if ($code == null) $code = $this->__genGroup();
        //if the group doesn't exist in master table, add it
        if (!$this->checkGroup($code)) {
            $this->db->insert('groups', array('code' => $code, 'name' => $name, 'description' => $description, 'year' => $this->current_year));
            $this->db->insert('groups_owner', array('code' => $code, 'owner' => $id, 'year' => $this->current_year)); //add the creator as group owner
            //add a new membership entry as well
        }
        $this->db->insert('users_groups', array('id' => $id, 'code' => $code, 'year' => $this->current_year));
    }

    /**
     * removes user from group by group code
     * @param int $id           user id
     * @param string $code      group code
     * @param int $year         year to check (default: $this->current_year)
     * @return bool             false if group not leaveable
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
     * @param $code             group code
     * @param int $year         year to check (default: $this->current_year)
     * @return void
     * @private
     */
    private function deleteGroup($code, $year = NULL)
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
     * @param int $year         year to check (default: $this->current_year)
     * @return mixed
     */
    public function listYearGroups($year = null)
    {
        if ($year == NULL) $year = $this->current_year;
        $this->db->from('groups')->where('year', $year);
        return $this->db->get()->result();
    }

    /**
     * checks if pairing was already run for a group
     * @param $code         group code
     * @param int $year     year to check (default: $this->current_year)
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
     * gets info for a single group
     * @param string $code             group code
     * @param int $year                year to check (default: $this->current_year)
     * @return mixed
     */
    public function groupInfo($code, $year = NULL)
    {
        if ($year == NULL) $year = $this->current_year;
        return $this->db->from('groups')->where(array('code' => $code, $year))->get()->result();
    }

    /**
     * gets all years of group data for a person, including owner
     * @param int $id           user id
     * @return array
     */
    public function groupInfoMultiple($id)
    {
        $query = $this->db->select('g.*, go.owner')->from('groups g')
            ->join('users_groups ug', 'ug.code = g.code AND g.year = ug.year', 'inner')
            ->join('groups_owner go', 'g.code=go.code AND g.year=go.year', 'left outer')
            ->where(array('ug.id' => $id))->get();
        $output = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $group) {
                $output[] = $group;
            }
        }
        return $output;
    }

    public function editGroup($code, $name, $description)
    {
        $this->db->where('code', $code)->update('groups', array('name' => $name, 'description' => $description));
        return true;
    }


    ////SETTINGS

    ////PROFILE RETRIEVAL
    /**
     * get user stats
     * @param int $id           user id
     * @return mixed
     */
    public function userStats($id)
    {
        $this->db->select(array('year_join', 'class'))->where(array('id' => $id));
        $query = $this->db->get('users');
        $row = $query->row();
        return $row;
    }

    /**
     * get number of gifts exchanged for user
     * @param int $id       user id
     * @return mixed
     */
    public function giftsExchanged($id)
    {
        $query = $this->db->select('give')->where(array('give' => $id))->get("pairs");
        return $query->num_rows();
    }

    ///DISCOVER GROUPS
    /**
     * List trending public groups
     * @param int $year         year to check (default: $this->current_year)
     * @return array|bool
     */
    public function listTrendingGroups($year = null)
    {
        if ($year == null) $year = $this->current_year;
        $data = false;
        foreach ($this->db->order_by("name", "asc")->get_where('groups', array("private" => 0, "year" => $year), 10)->result() as $row) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * Get the emails of group members
     * @param int $code         group code
     * @param int $year         year to check (default: $this->current_year)
     * @return array
     */
    public function getMemberEmails($code = null, $year = null)
    {
        if ($year == null) $year = $this->current_year;

        if ($code == null) {
            $whereClause = array('users_groups.year' => $year);
        } else {
            $whereClause = array('users_groups.code' => $code, 'users_groups.year' => $year);
        }

        $results = $this->db->select('email')
            ->distinct()
            ->from('users')
            ->join('users_groups', 'users.id = users_groups.id', 'inner')
            ->where($whereClause)
            ->get()
            ->result();

        $emails = array();
        foreach ($results as $result) {
            array_push($emails, $result->email);
        }

        return $emails;
    }
}