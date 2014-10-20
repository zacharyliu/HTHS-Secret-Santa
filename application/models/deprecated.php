<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Deprecated model functions
 * NOTE: Do not use this model in production.
 */


class Deprecated extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        die("Use of Deprecated.php model prohibited."); //Prohibit use of model

    }

}
