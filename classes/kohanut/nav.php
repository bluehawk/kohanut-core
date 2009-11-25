<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohanut_Nav {

	// protected data
	protected $id;
	protected $name;
	protected $url;
	
	// array of Kohanut_Nav childred
	protected $children = array();
	
	// pointer to parent node
	protected $parent = null;
	
	// what type of node is this? used for rendering nav menus
    public $isfirst = false;
    public $islast = false;
    public $iscurrent = false;
	
	// constructor, only used when creating the root, or by addchild() below
	public function __construct($id,$name,$url)
	{
		$this->id = $id;
		$this->name = $name;
		$this->url = $url;
	}

	/**
	 * parent
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
		$new = New Kohanut_Nav($id,$name,$url);
		$new->parent = &$this;
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
		// open the mainnav ul
		$out = "\n<!-- Begin Main Nav -->\n<ul class='mainnav'>";
		// mark first and last
		$this->children[0]->isfirst = true;
		end($this->children)->islast = true;
		// render children
		foreach($this->children as $child) {
			$out .= $child->_render();
		}
		// close mainnav ul, and return output
		$out .= "</ul>\n<!-- End Main Nav -->\n";
		return $out;
	}
	
	/**
	 * _render
	 *
	 * this is a private sub-function of render()
	 */
	private function _render()
	{
		$out = '<li';
		// do we have any classes?
		if ($this->isfirst OR $this->islast OR $this->iscurrent)
		{
			$out .= ' class="' . trim( ($this->isfirst?'first ':'') . ($this->islast?'last ':'') . ($this->iscurrent?'current':'')) . '"';
		}
		$out .= '><a href="' . $this->url . '">' . $this->name . "</a>";
		// do we have children?
		if (count($this->children)) {
			// mark first and last
			$this->children[0]->isfirst = true;
			end($this->children)->islast = true;
			// and render children
			$out .= "<ul>";
			foreach($this->children as $child) {
				$out .= $child->_render();
			}
			$out .= "</ul>";
		}
		$out .= '</li>';
		return $out;
	}
	
}