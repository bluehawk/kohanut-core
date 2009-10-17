<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohanut_element_Model extends ORM {

    protected $has_one = array();
    protected $has_many = array();
    protected $belongs_to = array();
    protected $has_and_belongs_to_many = array();

    public $table_name = "element_NOTYPESELECT";
    
    public function __construct($type) {
        $this->table_name = "element_" . inflector::plural($type);
        parent::__construct();
    }
    

}

?>