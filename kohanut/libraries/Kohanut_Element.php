<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohanut_Element_Core {
    
    // the id of the current element
    protected $id;
    
    // true for unique elements like content, false for reusable things like snippets or images
    protected static $_unique = true;
    
    // show a tab in the main nav
    protected static $_mainnavtab = true;
    
    // construct
    public function __construct()
    {
        
    }

    /* add
     * this function will create a new item of this type
     * like on the page, when viewed normally
     */
    public function add()
    {
    }



    /**
     * this function should simply echo whatever you want the element to look
     * like on the page, when viewed normally
     */
    public function render()
    {
    }
    
    /**
     * this should RETURN a VIEW of the edit form, for editing the element in
     * the admin side of it.
     *
     * @param   element     the orm element object to be edited
     * @return  view        the view that has the form for editing
     */
    public function edit($element)
    {
    }
    
    /**
     * This should find an element with the name specified and set $this->id to
     * the correct id.  Return true if an element was found, false otherwise
     *
     * @param  string  Name of the element to find
     * @return boolean
     */
    public function find($name)
    {
        return false;
    }
    
}

?>