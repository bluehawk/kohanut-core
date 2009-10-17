<?php defined('SYSPATH') OR die('No direct access allowed.');

class Page_Model extends ORM_MPTT {

    // default max depth of navigations, can be overwritten on call of main_nav, etc
    protected static $max_depth = 2;
    
    // overwrite the views directory
    protected $directory = 'kohanut';
    
    
    public function rootnode() {
        return $this->root();
    }
    
    /**
     * Overloaded render_descendants from ORM_MPTT
     * I added max_depth.
     *
     * Generates the HTML for this node's descendants
     *
     * @param   string  pagination style
     * @param   boolean include this node or not.
     * @return  string  pagination html
     */
    public function render_descendants($style = NULL, $self = FALSE, $direction = 'ASC',$max_depth = NULL)
    {
        if ($max_depth === NULL)
        {
            // use default depth
            $max_depth = $this->max_depth;
        }
        $nodes = $this->descendants($self, $direction)->where(array("lvl <="=>$max_depth))->find_all();
        
        if ($style === NULL)
        {
            // Use default style
            $style = $this->style;
        }

        
        return View::factory($this->directory.$style, array('nodes' => $nodes,'level_column' => $this->level_column))->render();
    }

}

?>