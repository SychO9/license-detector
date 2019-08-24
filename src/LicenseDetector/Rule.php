<?php

namespace LicenseDetector;

class Rule
{
    /**
     * @var array
     */
    const TYPES = ['permissions', 'conditions', 'limitations'];

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
     * @var string
     */
    protected $tag;

    /**
     * @var bool
     */
    protected $value;

    /**
     * Constructor
     */
    public function __construct($description, $label, $tag, RuleType $type = null)
    {
        $this->description = $description;
        $this->label = $label;
        $this->tag = $tag;
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
     * @return void
     */
    public function setValue(bool $v)
    {
        $this->value = $v;
    }
}