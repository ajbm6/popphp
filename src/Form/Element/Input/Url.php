<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp2
 * @category   Pop
 * @package    Pop_Form
 * @author     Nick Sagona, III <info@popphp.org>
 * @copyright  Copyright (c) 2009-2014 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Form\Element\Input;

/**
 * Form url element class
 *
 * @category   Pop
 * @package    Pop_Form
 * @author     Nick Sagona, III <info@popphp.org>
 * @copyright  Copyright (c) 2009-2014 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0a
 */

class Url extends AbstractInput
{

    /**
     * Element attributes
     * @var array
     */
    protected $attributes = ['type' => 'url'];

    /**
     * Constructor
     *
     * Instantiate the url input form element.
     *
     * @param  string $name
     * @param  string $value
     * @param  string $indent
     * @return Url
     */
    public function __construct($name, $value = null, $indent = null)
    {
        parent::__construct($name, $value, $indent);
        $this->setAttributes(['name' => $name, 'id' => $name, 'value' => $value]);
    }

    /**
     * Set whether the form element is required.
     *
     * @param  boolean $required
     * @return Url
     */
    public function setRequired($required)
    {
        $this->setAttribute('required', 'required');
        return parent::setRequired($required);
    }

}
