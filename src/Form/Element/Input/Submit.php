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

use Pop\Form\Element\AbstractElement;

/**
 * Form submit element class
 *
 * @category   Pop
 * @package    Pop_Form
 * @author     Nick Sagona, III <info@popphp.org>
 * @copyright  Copyright (c) 2009-2014 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0a
 */

class Submit extends AbstractElement
{

    /**
     * Constructor
     *
     * Instantiate the submit input form element.
     *
     * @param  string $name
     * @param  string $value
     * @param  string $indent
     * @return Submit
     */
    public function __construct($name, $value = null, $indent = null)
    {
        $this->type = 'input';
        parent::__construct($this->type, null, null, false, $indent);

        $this->setAttributes(['type' => 'submit', 'name' => $name, 'id' => $name, 'value' => $value]);
        $this->setValue($value);
        $this->setName($name);
    }

}