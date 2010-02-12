<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut Page Model
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Model_Kohanut_Page extends Sprig_MPTT {

	protected $_directory = '';

	protected function _init()
	{
		
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			
			// url and display name
			'url' => new Sprig_Field_Char(array(
				'empty' => TRUE,
				'default' => NULL,
			)),
			'name' => new Sprig_Field_Char,
			
			//layout
			'layout'  => new Sprig_Field_BelongsTo(array(
				'model' => 'kohanut_layout',
				'column' => 'layout',
			)),
			
			// nav info
			'islink'   => new Sprig_Field_Boolean(array('append_label'=>false,'default'=>false)),
			'shownav'  => new Sprig_Field_Boolean(array('append_label'=>false,'default'=>true)),
			'showmap'  => new Sprig_Field_Boolean(array('append_label'=>false,'default'=>true)),
			
			// meta datums
			'title'    => new Sprig_Field_Char(array('empty'=>true)),
			'metadesc' => new Sprig_Field_Text(array('empty'=>true)),
			'metakw'   => new Sprig_Field_Text(array('empty'=>true)),
			
			//MPTT
			'lft' => new Sprig_Field_MPTT_Left,
			'rgt' => new Sprig_Field_MPTT_Right,
			'lvl' => new Sprig_Field_MPTT_Level,
			'scp' => new Sprig_Field_MPTT_Scope,
			
		);
	
	}
	
	/**
	 * Create a new page in the tree as a child of $parent
	 *
	 *    if $location is "first" or "last" the page will be the first or last child
	 *    if $location is an int, the page will be the next sibling of page with id $location
	 * @param  Kohanut_Page  the parent
	 * @param  string/int    the location
	 * @return void
	 */
	public function create_at($parent, $location = 'last')
	{
		// Make sure a layout is set if this isn't an external link
		if ( ! $this->islink AND empty($this->layout->id))
		{
			throw new Kohanut_Exception("You must select a layout for a page that is not an external link.");
		}
		
		// Create the page as first child, last child, or as next sibling based on location
		if ($location == 'first')
		{
			$this->insert_as_first_child($parent);
		}
		else if ($location == 'last')
		{
			$this->insert_as_last_child($parent);
		}
		else
		{
			$target = Sprig::factory('kohanut_page',array('id'=> (int) $location))->load();
			if ( ! $target->loaded())
			{
				throw new Kohanut_Exception("Could not create page, could not find target for insert_as_next_sibling id: " . (int) $location);
			}
			$this->insert_as_next_sibling($target);
		}
	}
	
	public function move_to($action,$target)
	{
		// Find the target
		$target = Sprig::factory('kohanut_page',array('id'=>$target))->load();
		
		// Make sure it exists
		if ( !$target->loaded())
		{
			throw new Kohanut_Exception("Could not move page, target page did not exist." . (int) $target->id );
		}
		
		if ($action == 'before')
			$this->move_to_prev_sibling($target);
		elseif ($action == 'after')
			$this->move_to_next_sibling($target);
		elseif ($action == 'first')
			$this->move_to_first_child($target);
		elseif ($action == 'last')
			$this->move_to_last_child($target);
		else
			throw new Kohanut_Exception("Could not move page, action should be 'before', 'after', 'first' or 'last'.");
	}
	
	/**
	 * On update, make sure layout is set if its not an external link
	 */
	public function update()
	{
		// Make sure a layout is set if this isn't an external link
		if ( ! $this->islink AND empty($this->layout->id))
		{
			throw new Kohanut_Exception("You must select a layout for a page that is not an external link.");
		}
		parent::update();
	}
	
	/**
	 * Renders the page
	 *
	 * @returns a view file
	 */
	public function render()
	{
		
		if ( ! $this->loaded())
		{
			throw new Kohanut_Exception("Page render failed because page was not loaded.",array(),404);
		}
		
		Kohanut::$page = $this;
		
		// Build the view
		return new View('kohanut/xhtml', array('layoutcode' => $this->layout->load()->render()));
		
	}
	
	public function nav_nodes($depth)
	{
		$query = DB::select()
			->where($this->left_column, '>=', $this->{$this->left_column})
			->where($this->right_column, '<=', $this->{$this->right_column})
			->where($this->scope_column, '=', $this->{$this->scope_column})
			->where($this->level_column, '<=',$this->{$this->level_column} + $depth)
			->where('shownav','=',true)
			->order_by($this->left_column, 'ASC');
		
		return Sprig_MPTT::factory($this->_model)->load($query,FALSE);
	}
	
	
	/** overload values to fix checkboxes
	 *
	 * @param array values
	 * @return $this
	 */
	public function values(array $values)
	{
		if ($this->loaded()){
			$new = array(
				'islink'  => 0,
				'showmap' => 0,
				'shownav' => 0.
			);
			return parent::values(array_merge($new,$values));
		}
		else
		{
			return parent::values($values);
		}
	}
}