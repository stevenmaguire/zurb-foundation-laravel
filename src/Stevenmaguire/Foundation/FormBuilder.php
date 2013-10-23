<?php namespace Stevenmaguire\Foundation;

use Illuminate\Config\Repository;
use Illuminate\Support\MessageBag;

class FormBuilder extends \Illuminate\Html\FormBuilder 
{	

	/**
     * Illuminate config repository.
     *
     * @var Illuminate\Config\Repository
     */
    protected $config;

	/**
     * Illuminate errors messagebag.
     *
     * @var Illuminate\Support\MessageBag
     */
    protected $errors;

	public function __construct($html, $url, $token, $translator,Repository $config)
	{
		$this->config = $config;
		parent::__construct($html, $url, $token, $translator);
	}

	/**
	 * Create a text input field.
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @param  array   $options
	 * @return string
	 */
	public function ftext($name, $options = array())
	{
		$this->getErrorClass($name,$options);
		$tags = array();
		$tags['label'] = $this->getLabelTag($name,$options);
		$tags['input'] = $this->text($name, null, $options);
		$tags['error'] = $this->getErrorTag($name);
		return implode('',$tags);
	}
	
	/**
	 * Create a password input field.
	 *
	 * @param  string  $name
	 * @param  array   $options
	 * @return string
	 */
	public function fpassword($name, $options = array())
	{
		$this->getErrorClass($name,$options);
		$tags = array();
		$tags['label'] = $this->getLabelTag($name,$options);
		$tags['input'] = $this->input('password',$name, null, $options);
		$tags['error'] = $this->getErrorTag($name);
		return implode('',$tags);
	}	

	/**
	 * Create a new model based form builder.
	 *
	 * @param  mixed  $model
	 * @param  array  $options
	 * @return string
	 */
	public function fmodel($model, array $options = array(),MessageBag $errors)
	{
		$this->model = $model;
		$this->errors = $errors;
		return $this->open($options);
	}	

	private function getErrorClass($name,&$options = array())
	{
		if (isset($options['class']))
			$options['class'] .= ($this->errors->has($name) ? ' error' : '');
		else if ($this->errors->has($name))
			$options['class'] = 'error';
	}

	private function getLabelTag($name,&$options = array())
	{
		if (isset($options['label']))
		{			
			$tag = '<label for="'.$name.'" class="'.($this->errors->has($name) ? 'error' : '').'">'.$options['label'].'</label>';
			unset($options['label']);
			return $tag;
		}
	}

	private function getErrorTag($name)
	{
		return ($this->errors->has($name) ? '<small class="error">'.implode(' ',$this->errors->get($name)).'</small>' :'' );
	}
}