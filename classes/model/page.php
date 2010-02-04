<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohanut Page Model
 *
 * @package    Kohanut
 * @author     Michael Peters
 * @copyright  (c) Michael Peters
 * @license    http://kohanut.com/license
 */
class Model_Page extends Sprig_MPTT {

	protected $_directory = 'kohanut';

	protected function _init()
	{
		
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			
			// url and display name
			'url' => new Sprig_Field_Char,
			'name' => new Sprig_Field_Char,
			
			//layout
			'layout'  => new Sprig_Field_BelongsTo(array(
				'model' => 'Layout',
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
	 * Find a page with the specified id, returns that page, or false if not found
	 *
	 * @param   int  id of page to find
	 * @return  page or false
	 */
	public static function find($id)
	{
		// Cast to int for safety
		$id = (int) $id;
		$page = Sprig::factory('page',array('id'=>$id))->load();
		
		
		if ( ! $page->loaded())
		{
			return false;
		}
		return $page;
	}
	
	/** overload values to fix checkboxes
	 *
	 * @param array values
	 * @return $this
	 */
	public function _values(array $values)
	{
		$new = array(
			'islink'  => 0,
			'showmap' => 0,
			'shownav' => 0
		);
		return parent::values(array_merge($new,$values));
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
	
	/**
	 *
	 */

}