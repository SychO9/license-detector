<?php
/**
 * @package php-license-detector
 * @author Sami "SychO" Mazouz
 * @version 1.0
 * @license MIT
 */

namespace LicenseDetector;

use Symfony\Component\Yaml\Yaml;
use RecursiveDirectoryIterator;

class Detector
{
    /**
     * @var array
     */
    protected $paths = [
        'rules' => __DIR__ . '/../vendor/choosealicense.com/_data/rules.yml',
        'licenses' => __DIR__ . '/../vendor/choosealicense.com/_licenses'
    ];

    /**
     * @var string
     */
    public static $rules = [];

    /**
     * @var string
     */
    public static $licenses = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fillRules();
        $this->fillLicenses();
    }

    /**
     * @return void
     */
    public function fillRules()
    {
        if (!empty(self::$rules))
            return;

        $rule_types = Yaml::parseFile($this->paths['rules']);

        foreach ($rule_types as $rt => $rules)
        {
            foreach ($rules as $rule)
            {
                self::$rules[$rule['tag']] = new Rule(
                    $rule['tag'],
                    $rule['description'],
                    $rule['label'],
                    new RuleType($rt)
                );
            }
        }
    }

    /**
     * @return void
     */
    public function fillLicenses()
    {
        if (!empty(self::$licenses))
            return;

        $_licenses = new RecursiveDirectoryIterator($this->paths['licenses']);

        foreach ($_licenses as $fileinfo)
        {
            if (!$fileinfo->isFile())
                continue;

            $license = new License(file_get_contents($fileinfo->getPathname()));
            self::$licenses[strtolower($license->spdx_id)] = $license;
        }
    }

    /**
     * @return LicenseDetector\License
     */
    public function parseByPath($path)
    {
        if (!file_exists($path))
            throw new \Exception('Error: LICENSE file not found.');

        return new License(file_get_contents($path));
    }

    /**
     * @return LicenseDetector\License
     */
    public function parse(string $contents)
    {
        return new License($contents);
    }
}