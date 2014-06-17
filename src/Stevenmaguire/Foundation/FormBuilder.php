<?php namespace Stevenmaguire\Foundation;

use Illuminate\Support\MessageBag as MessageBag;
use Illuminate\Session\Store as Session;

class FormBuilder extends \Illuminate\Html\FormBuilder
{

    protected $local_errors;
    protected $foundation_labels = [];
    protected static $checkbox_count = [];
    protected static $radio_count = [];

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
     * Create a form input field.
     *
     * @param  string  $type
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function input($type, $name, $value = null, $options = array())
    {
      $this->getErrorClass($name,$options);
      $tags = array();
      $tags['input'] = parent::input($type, $name, $value, $options);
      $tags['error'] = $this->getErrorTag($name);
      $is_checkable = in_array($type, ['checkbox', 'radio']);
      $id = $this->getIdAttribute($name, $options, $is_checkable);
      
      return $this->renderWithLabel(implode(' ',$tags), $name, $id, $is_checkable);
    }

    /**
     * Create a form textarea field.
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
        return $this->renderWithLabel(implode(' ',$tags), $name);
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
        return $this->renderWithLabel(implode(' ',$tags), $name);
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
     * @param  array   $attributes ref
     * @return void
     */
    private function getErrorClass($name, &$attributes)
    {
      // If no error is set we don't need to do anything else
      if ( $this->hasError($name) ) {
        if ( isset($attributes['class']) )
            $attributes['class'] .= ' error';
        else
            $attributes['class'] = 'error';
      }
    }

    /**
     * Create Foundation 5 "error" label.
     *
     * @param  string  $name
     * @return string
     */
    private function getErrorTag($name)
    {
        return ($this->hasError($name) ? '<span class="error">'.implode(' ', $this->getError($name)).'</span>' :'' );
    }
    
    /**
     * Create a checkable input field (checkbox/radio).
     *
     * @param  string  $type
     * @param  string  $name
     * @param  mixed   $value
     * @param  bool    $checked
     * @param  array   $options
     * @return string
     */
    protected function checkable($type, $name, $value, $checked, $options)
    {
      $this->setCheckableAttributeID($type, $name, $options);

      return parent::checkable($type, $name, $value, $checked, $options);
    }
    
    /**
     * Generates an unique ID for a checkable fields, if not already defined,
     * this is useful for having a "clickable" label for every checkbox or radio
     * item in a group.
     *
     * @param  string  $type
     * @param  string  $name
     * @param  array   $attributes ref
     */
    private static function setCheckableAttributeID($type, $name, &$attributes)
    {
      // Do nothing if ID already defined
      if ( !empty($attributes['id']) ) {
        return;
      }
      
      switch ( $type ) {
        case 'checkbox':
          $id = $name.'-'.self::checkboxCounter($name);
          break;
        
        case 'radio':
          $id = $name.'-'.self::radioCounter($name);
          break;
      }

      $attributes['id'] = $id;
    }
    
    /**
     * Returns the number of checboxes for a given group name
     *
     * @param  string  $name
     * @return int
     */
    private static function checkboxCounter($name)
    {
      if ( !array_key_exists( $name, self::$checkbox_count ) ) {
        self::$checkbox_count[$name] = 0;
      }
      
      return self::$checkbox_count[$name]++;
    }
    
    /**
     * Returns the number of radios for a given group name
     *
     * @param  string  $name
     * @return int
     */
    private static function radioCounter($name)
    {
      if ( !array_key_exists( $name, self::$radio_count ) ) {
        self::$radio_count[$name] = 0;
      }
      
      return self::$radio_count[$name]++;
    }
    
    /**
     * Get the ID attribute for a field name.
     *
     * @param  string  $name
     * @param  array   $attributes
     * @return string
     */
    public function getIdAttribute($name, $options, $checkable_type = null)
    {
      if ( $checkable_type ) {
        self::setCheckableAttributeID($checkable_type, $name, $options);
        return $options['id'];
      }
      
      return parent::getIdAttribute($name, $options);
    }
    
    
    /**
     * Calling method **before** generating the item sets the desired label for
     * a form item. *This method is chainable.*
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return FormBuilder
     */
    public function withLabel($name, $value = null, $options = array())
    {
      // Comatibility with the old implementation.
      $this->labels[] = $name;
      
      $this->foundation_labels[$name] = [
        'value' => e($value),
        'options' => $options
      ];

      return $this;
    }
    
    /**
     * Renders an element adding a wrapper label. As suggested Foundation 5:
     * [ http://foundation.zurb.com/docs/components/forms.html ]
     */
    private function renderWithLabel($html, $name, $id = null, $checkable = FALSE)
    {
      if ( array_key_exists($name, $this->foundation_labels) ) {
        $label_data = $this->foundation_labels[$name];
        
        $this->getErrorClass($name, $label_data['options']);
        $rendered_options = $this->html->attributes( $label_data['options'] );
        $label = $this->formatLabel($name, $label_data['value'] );

        if ($id == null) {
          $id = self::getIdAttribute($name, $label_data['options'], $checkable);
        }
        
        if ( $checkable ) {
          return "<label for='{$id}' {$rendered_options}>{$html}&nbsp;{$label}</label>";
        } else {
          return "<label for='{$id}' {$rendered_options}>{$label}{$html}</label>";
        }
      } else {
        return $html;
      }
    }
}
