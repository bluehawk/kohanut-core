<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohanut_Nav {

	// id name and url
	public $id;
	public $name;
	public $url;
	
	// Array of Kohanut_Nav childred
	protected $children = array();
	
	// Pointer to parent node
	protected $parent = NULL;
	
	// What type of node is this? used for rendering nav menus
    public $isfirst = false;
    public $islast = false;
    public $iscurrent = false;
	
	// Constructor, only used when creating the root, or by addchild() below
	public function __construct($id,$name,$url)
	{
		$this->id = $id;
		$this->name = $name;
		$this->url = $url;
	}

	/**
	 * Parent
	 * @return a link to this nodes parent
	 *
	 * note that this can return null you if request the roots parent
	 */
	public function &parent()
	{
		return $this->parent;
	}
	
	/**
	 * addchild
	 * @param	id	id of the node
	 * @param	name	display name of the node
	 * @param	url	url to link to
	 * @return	a pointer to newly created child
	 *
	 * creates a new child and returns a pointer to the new node
	 */
	public function &addchild($id,$name,$url)
	{
		// Create a new Nav item and sets its parent to $this
		$new = New Kohanut_Nav($id,$name,$url);
		$new->parent = &$this;
		
		// Add this item to the children array
		$this->children[] = &$new;
		return $new;
	}
	
	/**
	 * render
	 * @return	the html of the menu
	 *
	 * this will render the menu structure of this, and all child nodes
	 */
	public function render() {
		
		// Open the ul
		$out = "<ul>";
		
		// Mark first and last
		$this->children[0]->isfirst = true;
		end($this->children)->islast = true;
		
		// Render children
		foreach($this->children as $child) {
			$out .= $child->_render();
		}
		
		// Close the ul, return the output
		$out .= "</ul>";
		return $out;
	}
	
	/**
	 * _render
	 *
	 * this is a private sub-function of render()
	 */
	private function _render()
	{
		// Open the current item
		$out = '<li';
		
		// Do we have any classes?
		if ($this->isfirst OR $this->islast OR $this->iscurrent)
		{
			$out .= ' class="' . trim( ($this->isfirst?'first ':'') . ($this->islast?'last ':'') . ($this->iscurrent?'current':'')) . '"';
		}
		$out .= '><a href="' . $this->url . '">' . $this->name . "</a>";
		
		// Do we have children?
		if (count($this->children)) {
			
			// Mark first and last
			$this->children[0]->isfirst = true;
			end($this->children)->islast = true;
			
			// Render children
			$out .= "<ul>";
			foreach($this->children as $child) {
				$out .= $child->_render();
			}
			$out .= "</ul>";
		}
		
		// Close current item, return the output
		$out .= '</li>';
		return $out;
	}
	
}