<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Model_Layout extends Sprig {

	protected function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			
			'name' => new Sprig_Field_Char(array('label'=>'Name')),
			'desc' => new Sprig_Field_Char(array('label'=>'Description')),
			'code' => new Sprig_Field_Text(array('label'=>'Code')),
			
			'pages' => new Sprig_Field_HasMany(array(
				'model' => 'page',
			)),
			
		);
	}
	
	/**
	 * Find a layout with the specified id, returns that layout, or false if not found
	 *
	 * @param   int  id of layout to find
	 * @return  layout or false
	 */
	public static function find($id)
	{
		// Cast to int for safety
		$id = (int) $id;
		$layout = Sprig::factory('layout',array('id'=>$id))->load();
		
		
		if ( ! $layout->loaded())
		{
			return false;
		}
		return $layout;
	}
	
	public function render()
	{
		// Ensure the layout is loaded
		$this->load();
		
		if ( ! $this->loaded())
		{
			return "Layout Failed to render because it wasn't loaded.";
		}
		
		/**
		 * eval() the layout code to a buffer
		 * The 'Kohanut::layout_area()' functions inside the layout code will
		 * pull all the needed contents.
		 * Note: the space after the <?php tag in essential, as it somehow
		 * fixes an "unexpected $end in eval()'d code" error.
		 */
		try {
			// Create the output buffer
			ob_start();
			
			// Eval the layout code, save, and delete the buffer
			eval('?>' . $this->code . '<?php ');
			$layoutcode = ob_get_contents();
			ob_end_clean();
		}
		catch (Exception $e)
		{
			ob_end_clean();
			$layoutcode = "<h1>There was an error processing the layout code.</h1><p>" . $e . "</p>";
		}
		
		
		// And return the output
		return $layoutcode;
	}

}