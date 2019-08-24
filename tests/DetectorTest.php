<?php
declare(strict_types=1);

use LicenseDetector\Detector;
use PHPUnit\Framework\TestCase;

final class DetectorTest extends TestCase
{
    /**
     * @var LicenseDetector\Detector
     */
    private $detector;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->detector = new Detector();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->detector = NULL;
    }

    /**
     * @return void
     */
    public function testParse(): void
    {
        $license = $this->detector->parseByPath('LICENSE');
        $license->printDebug();

        $this->assertTrue($license->isValid());
    }
}