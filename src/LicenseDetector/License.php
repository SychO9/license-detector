<?php

namespace LicenseDetector;

use Symfony\Component\Yaml\Yaml;

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
        return preg_replace('/\s/s', '', $this->body);
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
     * @return void
     */
    public function setAdvanced(array $data)
    {
        foreach ($data as $k => $v)
            $this->{str_replace('-', '_', $k)} = $v;

        $this->setRules($data);
    }

    /**
     * @return void
     */
    public function setRules(array $data)
    {
        foreach (Rule::TYPES as $rt)
        {
            $rules = $data[$rt] ?? [];
            $this->rules[$rt] = new RuleType($rt, $rules);
        }
    }

    /**
     * @return void
     */
    public function matchToLicense()
    {
        foreach (Core::$licenses as $license)
        {
            similar_text($this->getCleanBody(), $license->getCleanBody(), $percent);

            if (!empty($this->stats['highest_percentage']) && $percent < $this->stats['highest_percentage'])
                continue;

            $this->stats['highest_match'] = $license;
            if (($this->stats['highest_percentage'] = $percent) > 90)
            {
                $data = [];
                foreach ($license as $key => $property)
                    $data[$key] = $property;

                $this->stats['percentage'] = $percent;
                $this->setAdvanced($data);
                break;
            }
        }
    }

    /**
     * @return string
     */
    public function show()
    {
        $echo = 'Matched: ' . ($this->title ?? '<em>None</em>') . "\n";
        $echo .= 'Percentage: ' . ($this->stats['percentage'] ?? '0') . "%\n\n";
        $echo .= 'Highest match: ' . ($this->stats['highest_match']->title ?? '<em>None</em>') . "\n";
        $echo .= 'Percentage: ' . ($this->stats['highest_percentage'] ?? '0') . "%";

        return '<pre>' . $echo . '</pre>';
    }
}