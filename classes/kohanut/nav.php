<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Kohanut_Nav is used to draw the navigations.  The view/nav.php file is run first, which creates these.
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Kohanut_Nav {

	// id name and url
	public $id;
	public $name;
	public $url;
	
	// Array of Kohanut_Nav childred
	protected $children = array();
	
	// Pointer to parent node
	protected $parent = NULL;
	
	// Above is the element thats one higher than the highest shown, its the header above secondary navs
	protected $above = NULL;
	
	// What type of node is this? used for rendering nav menus
    public $isfirst = false;
    public $islast = false;
    public $iscurrent = false;
	
	// Constructor, only used when creating the root, or by addchild() below
	public function __construct($id,$name,$url,$above = NULL)
	{
		$this->id = $id;
		$this->name = $name;
		$this->url = $url;
		$this->above = $above;
	}
	
	public static $defaults = array(
			
		// Options for the header before the nav
		'header'       => true,
		'header_elem'  => 'h3',
		'header_class' => '',
		'header_id'    => '',
		
		// Options for the list itself
		'class'   => '',
		'id'      => '',
		'depth'   => 2,
		
		// Options for items
		'current_class' => 'current',
		'first_class' => 'first',
		'last_class'  => 'last',

	);

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
	 * this will render the menu structure of this, and all child nodes
	 * 
	 * @return	the html of the menu
	 */
	public function render($options = array())
	{
		$defaults = array(
			
			// Options for the header before the nav
			'header'       => false,
			'header_elem'  => 'h3',
			'header_class' => '',
			'header_id'    => '',
			
			// Options for the list itself
			'class'   => '',
			'id'      => '',
			'depth'   => 2,
			
			// Options for items
			'current_class' => 'current',
			'first_class' => 'first',
			'last_class'  => 'last',

		);
		
		$options = array_merge($defaults,$options);
		
		// Start the out
		$out = "";
		
		// Add the header
		if ($options['header'])
		{
			$out .= "<" . $options['header_elem'] .
			        ($options['header_class'] != '' ? " class='{$options['header_class']}'":'') .
					($options['header_id'] != '' ? " id='{$options['header_id']}'":'') . ">" .
					html::anchor($this->above->url,$this->above->name) .
					"</" . $options['header_elem'] . ">";
		}
		
		// Open the ul
		$out .= "<ul" . ($options['class'] != '' ? " class='{$options['class']}'":'') .
					   ($options['id'] != '' ? " id='{$options['id']}'":'') . ">";
		
		if (count($this->children))
		{
			// Mark first and last
			$this->children[0]->isfirst = true;
			end($this->children)->islast = true;
			
			// Render children
			foreach($this->children as $child) {
				$out .= $child->_render();
			}
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
		
		// Add in base_url, but make sure it does not having a trailing slash, and make sure url does.
		$out .= '>';
		
		$out .= html::anchor($this->url,$this->name);
		
		// Do we have children?
		if (count($this->children))
		{
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