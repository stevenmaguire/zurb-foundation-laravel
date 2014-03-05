<?php namespace Stevenmaguire\Foundation;

use Illuminate\Support\MessageBag as MessageBag;
use Illuminate\Session\Store as Session;

class FormBuilder extends \Illuminate\Html\FormBuilder
{

	protected $local_errors;

	public function __construct($html, $url, $token, $translator, $errors = null)
	{
		$this->local_errors = ($errors != null ? $errors : new MessageBag);
		parent::__construct($html, $url, $token, $translator);
	}

	/**
	 * Check if session in parent class has error
	 *
	 * @return bool
	 */
    private function hasError($name = null)
    {
    	return $this->local_errors->has($name);
    }

	/**
	 * Get error text from session in parent class
	 *
	 * @return string
	 */
    private function getError($name = null)
    {
    	return $this->local_errors->get($name);
    }

	/**
	 * Create a text input field.
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @param  array   $options
	 * @return string
	 */
	public function text($name, $value = NULL, $options = array())
	{
		$this->getErrorClass($name,$options);
		$tags = array();
		$tags['input'] = parent::text($name, $value, $options);
		$tags['error'] = $this->getErrorTag($name);
		return implode('',$tags);
	}

	/**
	 * Create a password input field.
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @param  array   $options
	 * @return string
	 */
	public function password($name, $value = NULL, $options = array())
	{
		$this->getErrorClass($name,$options);
		$tags = array();
		$tags['input'] = parent::password($name, $options);
		$tags['error'] = $this->getErrorTag($name);
		return implode('',$tags);
	}

	/**
	 * Create a email input field.
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @param  array   $options
	 * @return string
	 */
	public function email($name, $value = NULL, $options = array())
	{
		$this->getErrorClass($name,$options);
		$tags = array();
		$tags['input'] = parent::email($name, $value, $options);
		$tags['error'] = $this->getErrorTag($name);
		return implode('',$tags);
	}

	/**
	 * Create a textarea input.
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @param  array   $options
	 * @return string
	 */
	public function textarea($name, $value = null, $options = array())
	{
		$this->getErrorClass($name,$options);
		$tags = array();
		$tags['input'] = parent::textarea($name, $value, $options);
		$tags['error'] = $this->getErrorTag($name);
		return implode('',$tags);
	}

	/**
	 * Create a select box field.
	 *
	 * @param  string  $name
	 * @param  array   $list
	 * @param  string  $selected
	 * @param  array   $options
	 * @return string
	 */
	public function select($name, $list = array(), $selected = null, $options = array())
	{
		$this->getErrorClass($name,$options);
		$tags = array();
		$tags['input'] = parent::select($name, $list, $selected, $options);
		$tags['error'] = $this->getErrorTag($name);
		return implode('',$tags);		
	}

	/**
	 * Create a select range field.
	 *
	 * @param  string  $name
	 * @param  string  $begin
	 * @param  string  $end
	 * @param  string  $selected
	 * @param  array   $options
	 * @return string
	 */
	public function selectRange($name, $begin, $end, $selected = null, $options = array())
	{
		$this->getErrorClass($name,$options);
		$tags = array();
		$tags['input'] = parent::selectRange($name, $begin, $end, $selected, $options);
		$tags['error'] = $this->getErrorTag($name);
		return implode('',$tags);				
	}

	/**
	 * Create a select month field.
	 *
	 * @param  string  $name
	 * @param  string  $selected
	 * @param  array   $options
	 * @return string
	 */
	public function selectMonth($name, $selected = null, $options = array())
	{
		$this->getErrorClass($name,$options);
		$tags = array();
		$tags['input'] = parent::selectMonth($name, $selected, $options);
		$tags['error'] = $this->getErrorTag($name);
		return implode('',$tags);				
	}

	/**
	 * Create a label.
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @param  array   $options
	 * @return string
	 */
	public function label($name, $value = NULL, $options = array())
	{
		$this->getErrorClass($name,$options);
		return parent::label($name, $value, $options);
	}	

	/**
	 * Insert 'error' class in $options array, if error found in session
	 *
	 * @param  string  $name
	 * @param  array   $options ref
	 * @return void
	 */
	private function getErrorClass($name,&$options = array())
	{
		if (isset($options['class']))
			$options['class'] .= ($this->hasError($name) ? ' error' : '');
		else if ($this->hasError($name))
			$options['class'] = 'error';
	}

	/**
	 * Create Foundation 4 "error" label.
	 *
	 * @param  string  $name
	 * @return string
	 */
	private function getErrorTag($name)
	{
		return ($this->hasError($name) ? '<small class="error">'.implode(' ',$this->getError($name)).'</small>' :'' );
	}
}
