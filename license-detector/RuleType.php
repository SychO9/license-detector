<?php
/**
 * @package php-license-detector
 * @author Sami "SychO" Mazouz
 * @version 1.0
 * @license MIT
 */

namespace LicenseDetector;

/**
 * Represents a type of rule; permissions, conditions or limitations.
 */
class RuleType
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $rules = [];

    /**
     * Constructor
     * @param string $name
     * @param array $rules (optional)
     */
    public function __construct(string $name, array $rules = null)
    {
        $this->name = $name;

        if (!empty($rules))
            $this->fillRules($rules);
    }

    /**
     * @param array $rules
     * @return void
     */
    public function fillRules(array $rules)
    {
        if (empty($rules))
            return;

        foreach ($rules as $rule)
        {
            $r = new Rule(
                $rule['tag'] ?? null,
                $rule['description'] ?? null,
                $rule['label'] ?? null
            );
            $r->setValue($rule['value'] ?? false);

            if (!empty($rule['tag']))
                $this->rules[$rule['tag']] = $r;
            else
                $this->rules[] = $r;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param LicenseDetector\RuleType $rule_type
     * @return bool
     */
    public function equals(RuleType $rule_type)
    {
        if ($this->name === $rule_type->name)
            return true;

        return false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}