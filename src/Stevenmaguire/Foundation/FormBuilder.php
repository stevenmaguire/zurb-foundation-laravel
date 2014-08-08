<?php namespace Stevenmaguire\Foundation;

use Illuminate\Support\MessageBag as MessageBag;

class FormBuilder extends \Illuminate\Html\FormBuilder
{

    /**
     * Local stash of errors from session
     *
     * @var Illuminate\Support\MessageBag
     */
    protected $local_errors;

    /**
     * Create new instance of this FormBuilder intercepter!
     *
     * @param  Illuminate\Routing\UrlGenerator   $url
     * @param  Illuminate\Html\HtmlBuilder       $html
     * @param  string                            $token
     * @param  Illuminate\Translation\Translator $translator
     * @param  mixed                             $errors
     *
     * @return void
     */
    public function __construct($html, $url, $token, $translator, $errors = null)
    {
        $this->local_errors = ($errors != null ? $errors : new MessageBag);
        parent::__construct($html, $url, $token, $translator);
    }

    /**
     * Create a email input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function email($name, $value = NULL, $options = [])
    {
        $this->addErrorClass($name, $options);
        $tags['input'] = parent::email($name, $value, $options);
        $tags['error'] = $this->getErrorTag($name);
        return $this->buildTags($tags);
    }

    /**
     * Create a label.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function label($name, $value = NULL, $options = [])
    {
        $this->addErrorClass($name, $options);
        return parent::label($name, $value, $options);
    }

    /**
     * Create a password input field.
     *
     * @param  string  $name
     * @param  array   $options
     *
     * @return string
     */
    public function password($name, $options = [])
    {
        $this->addErrorClass($name, $options);
        $tags['input'] = parent::password($name, $options);
        $tags['error'] = $this->getErrorTag($name);
        return $this->buildTags($tags);
    }

    /**
     * Create a select box field.
     *
     * @param  string  $name
     * @param  array   $list
     * @param  string  $selected
     * @param  array   $options
     *
     * @return string
     */
    public function select($name, $list = array(), $selected = null, $options = [])
    {
        $this->addErrorClass($name, $options);
        $tags['input'] = parent::select($name, $list, $selected, $options);
        $tags['error'] = $this->getErrorTag($name);
        return $this->buildTags($tags);
    }

    /**
     * Create a select month field.
     *
     * @param  string  $name
     * @param  string  $selected
     * @param  array   $options
     *
     * @return string
     */
    public function selectMonth($name, $selected = null, $options = [], $format = '')
    {
        $this->addErrorClass($name, $options);
        $tags['input'] = parent::selectMonth($name, $selected, $options);
        $tags['error'] = $this->getErrorTag($name);
        return $this->buildTags($tags);
    }

    /**
     * Create a select range field.
     *
     * @param  string  $name
     * @param  string  $begin
     * @param  string  $end
     * @param  string  $selected
     * @param  array   $options
     *
     * @return string
     */
    public function selectRange($name, $begin, $end, $selected = null, $options = [])
    {
        $this->addErrorClass($name, $options);
        $tags['input'] = parent::selectRange($name, $begin, $end, $selected, $options);
        $tags['error'] = $this->getErrorTag($name);
        return $this->buildTags($tags);
    }

    /**
     * Create a text input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function text($name, $value = NULL, $options = [])
    {
        $this->addErrorClass($name, $options);
        $tags['input'] = parent::text($name, $value, $options);
        $tags['error'] = $this->getErrorTag($name);
        return $this->buildTags($tags);
    }

    /**
     * Create a textarea input.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function textarea($name, $value = null, $options = [])
    {
        $this->addErrorClass($name, $options);
        $tags['input'] = parent::textarea($name, $value, $options);
        $tags['error'] = $this->getErrorTag($name);
        return $this->buildTags($tags);
    }

    /**
     * Insert 'error' class in $options array, if error found in session
     *
     * @param  string  $name
     * @param  array   $options ref
     *
     * @return void
     */
    private function addErrorClass($name, &$options = [])
    {
        if (isset($options['class'])) {
            $options['class'] .= ($this->hasError($name) ? ' error' : '');
        } elseif ($this->hasError($name)) {
            $options['class'] = 'error';
        }
    }

    /**
     * Concatenate and format the tags.
     *
     * @param  array  $tags
     *
     * @return string
     */
    private function buildTags($tags = [])
    {
        return implode('', $tags);
    }

    /**
     * Create Foundation 4 "error" label.
     *
     * @param  string  $name
     *
     * @return string
     */
    private function getErrorTag($name)
    {
        return ($this->hasError($name) ? '<small class="error">'.implode(' ',$this->getError($name)).'</small>' :'' );
    }

    /**
     * Get error text from session in parent class
     *
     * @param  string $name
     *
     * @return string
     */
    private function getError($name = null)
    {
        return $this->local_errors->get($name);
    }

    /**
     * Check if session in parent class has error
     *
     * @param  string $name
     *
     * @return bool
     */
    private function hasError($name = null)
    {
        return $this->local_errors->has($name);
    }

}
