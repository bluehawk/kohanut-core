<?php defined('SYSPATH') OR die('No direct access allowed.');

class Pagecontent_Model extends ORM {

    protected $has_one = array();
    protected $has_many = array();
    protected $belongs_to = array('page','elementtype');
    protected $has_and_belongs_to_many = array();

    

}

?>