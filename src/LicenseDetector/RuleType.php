<?php

namespace LicenseDetector;

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
     */
    public function __construct(string $name, array $rules = null)
    {
        $this->name = $name;

        if (!empty($rules))
            $this->fillRules($rules);
    }

    /**
     * @return void
     */
    public function fillRules(array $rules)
    {
        if (empty($rules))
            return;

        foreach ($rules as $rule)
        {
            $r = new Rule(
                $rule['description'] ?? null,
                $rule['label'] ?? null,
                $rule['tag'] ?? null
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