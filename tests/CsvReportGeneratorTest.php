<?php

namespace App\Tests;

use App\Entity\Job;
use App\Services\CsvReportGenerator;

class CsvReportGeneratorTest
{
    /**
     * @var CsvReportGenerator
     */
    private $generator;

    public function setUp()
    {
        $this->generator = new CsvReportGenerator();

        $this->prepareReportDir();
        $this->removeOldReport();
    }

    public function test_it_should_generate_csv_report()
    {
        $jobOffers = $this->prepareData();

        $this->generator->process($jobOffers);

        $this->assertReportContentEquals($this->getExpectedReport());
    }

    private function prepareReportDir()
    {
        if (!file_exists(self::REPORT_PATH)) {
            mkdir(self::REPORT_PATH, 0777);
        }
    }

    private function removeOldReport()
    {
        @unlink(self::REPORT_PATH.'report.csv');
    }

    private function prepareData()
    {
        $item1 = new Job(
            '__URL_1__',
            '__HEADLINE_1__',
            ['students'],
            true,
            '__DESCRIPTION_1__',
            null
        );

        $item2 = new Job(
            '__URL_2__',
            '__HEADLINE_2__',
            ['other-category'],
            false,
            '__DESCRIPTION_2__',
            2
        );

        return [
            $item1,
            $item2
        ];
    }

    private function getExpectedReport(): string
    {
        return <<<EOT
__URL_1__,__HEADLINE_1__,__DESCRIPTION_1__,true,n/a
__URL_2__,__HEADLINE_2__,__DESCRIPTION_2__,false,2
EOT;
    }

}