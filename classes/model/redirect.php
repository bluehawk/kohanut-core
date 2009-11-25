<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Model_Redirect extends Sprig {

	protected function _init()
	{
		$this->_fields += array(
			'id' => new Sprig_Field_Auto,
			'url' => new Sprig_Field_Char,
			'newurl' => new Sprig_Field_Char,
			'type' => new Sprig_Field_Enum(array(
				'choices' => array('301'=>'301','302'=>'302')
			)),
		);
	}
	
	/**
	 * Find a redirect from $url
	 *
	 * @return  boolean   true if found, false if not
	 */
	public function find($url) {
		// Check for a redirect at $url
		$this->url = $url;
		$this->load();
		return $this;
	}
	
	public function go() {
		
		// Make sure this redirect is loaded
		if ( $this->loaded())
		{
			
			if ($this->type == '301' || $this->type == '302')
			{
				// Redirect to the new url
				Kohana::$log->add('INFO', "Kohanut - Redirected '$this->url' to '$this->newurl' ($this->type)."); 
				Request::instance()->redirect($this->newurl,$this->type);
			}
			else
			{
				// This should never happen, log an error and display an error
				Kohana::$log->add('ERROR', "Kohanut - Could not redirect '$this->url' to '$this->newurl'. Unknown redirect type: ($this->type)");
				throw Kohanut_Exception("Unknown redirect type",array(),404);
			}
			
		}
	}
}