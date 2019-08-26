<?php
/**
 * @package php-license-detector
 * @author Sami "SychO" Mazouz
 * @version 1.0
 * @license MIT
 */

namespace LicenseDetector;

/**
 * Represents a rule
 */
class Rule
{
    /**
     * @var array
     */
    const TYPES = ['permissions', 'limitations', 'conditions'];

    /**
     * @var string
     */
    protected $tag;

    /**
     * @var LicenseDetector\RuleType
     */
    public $type;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var bool
     */
    protected $value;

    /**
     * Constructor
     * @param string $tag
     */
    public function __construct(string $tag, string $description = null, string $label = null, RuleType $type = null)
    {
        $this->tag = $tag;
        $this->description = $description;
        $this->label = $label;
        $this->type = $type;
        $this->value = false;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param bool $v
     * @return void
     */
    public function setValue(bool $v)
    {
        $this->value = $v;
    }
}