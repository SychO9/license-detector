<?php
/**
 * @package php-license-detector
 * @author Sami "SychO" Mazouz
 * @version 1.0
 * @license MIT
 */

namespace LicenseDetector;

use Symfony\Component\Yaml\Yaml;

/**
 * Instantiates a license and matches it to the list of existing licenses
 */
class License
{
    /**
     * @var string
     */
    protected $contents;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $spdx_id;

    /**
     * @var string
     */
    public $redirect_from;

    /**
     * @var string
     */
    public $featured;

    /**
     * @var string
     */
    public $hidden;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $how;

    /**
     * @var string
     */
    public $note;

    /**
     * @var string
     */
    public $using = [];

    /**
     * @var array
     */
    public $rules = [];

    /**
     * @var float
     */
    public $stats = [];

    /**
     * Constructor
     *
     * @param string $contents
     */
    public function __construct(string $contents)
    {
        $this->contents = $contents;
        $this->parse();
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @var string
     */
    public function getCleanBody()
    {
        return substr(preg_replace('/\s/s', '', $this->body), 0, 1000);
    }

    /**
     * @return array
     */
    public function getAllRules()
    {
        $rules = [];

        foreach ($this->rules as $type)
            $rules += $type->rules;

        return $rules;
    }

    /**
     * @return void
     */
    public function parse()
    {
        preg_match_all('/---\s(.*)\n---\s+(.*)/s', $this->contents, $matches, PREG_SET_ORDER, 0);

        if (!empty($matches[0][2]))
            $this->body = $matches[0][2];

        if (!empty($matches[0][1]))
        {
            try {
                $this->setAdvanced(Yaml::parse($matches[0][1]));
            } catch(\Exception $e) {
                echo ('Could not parse yaml content.');
            }
        }

        if (empty($matches))
        {
            $this->body = $this->contents;
            $this->matchToLicense();
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function setAdvanced(array $data)
    {
        foreach ($data as $k => $v)
            $this->{str_replace('-', '_', $k)} = $v;

        $this->fillRules($data);
    }

    /**
     * @param array $data
     * @return void
     */
    public function fillRules(array $data)
    {
        foreach (Rule::TYPES as $type)
        {
            if (!isset($data[$type]))
                continue;

            $this->rules[$type] = new RuleType($type);

            $rules = [];
            foreach (Detector::$rules as $rule)
                if (in_array($rule->getTag(), $data[$type]))
                    $rules[$rule->getTag()] = $rule;

            $this->rules[$type]->setRules($rules);
        }
    }

    /**
     * @return void
     */
    public function matchToLicense()
    {
        foreach (Detector::$licenses as $license)
        {
            similar_text($this->getCleanBody(), $license->getCleanBody(), $percent);

            if (!empty($this->stats['highest_percentage']) && $percent < $this->stats['highest_percentage'])
                continue;

            $this->stats['highest_match'] = $license;
            $this->stats['highest_percentage'] = $percent;
            if ($this->stats['highest_percentage'] > 90)
            {
                $data = [];
                foreach ($license as $key => $property)
                    if ($key !== 'contents' && $key !== 'body')
                        $data[$key] = $property;

                unset($data['stats']);

                $this->setAdvanced($data);
                $this->stats['percentage'] = $percent;
                break;
            }
        }
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return !empty($this->title);
    }

    /**
     * @return string
     */
    public function printDebug()
    {
        $echo = "\nMatched: " . ($this->title ?? '<em>None</em>') . "\n";
        $echo .= 'Percentage: ' . ($this->stats['percentage'] ?? '0') . "%\n\n";
        $echo .= 'Highest match: ' . ($this->stats['highest_match']->title ?? '<em>None</em>') . "\n";
        $echo .= 'Percentage: ' . ($this->stats['highest_percentage'] ?? '0') . "%";

        echo $echo;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }
}