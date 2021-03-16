<?php

namespace App\Tests;

use App\Entity\Job;
use App\Services\CsvReportGenerator;
use PHPUnit\Framework\TestCase;


class CsvReportGeneratorTest extends TestCase
{
    const REPORT_PATH = '/app';

    /**
     * @var CsvReportGenerator
     */
    private $generator;

    public function test_it_should_generate_csv_report()
    {
        $this->generator = new CsvReportGenerator(self::REPORT_PATH);
        $jobOffers = $this->prepareData();

        $this->generator->process($jobOffers);

        $this->assertReportContentEquals($this->getExpectedReport());
    }

    private function prepareData()
    {
        $item1 = new Job();
        $item1->setTitle('testTitle');
        $item1->setLevel('testSenior');
        $item1->setCategory('testCategory');
        $item1->setDescription('testDescription');
        $item1->setRequirements('testRequirments');
        $item1->setYearsOfExperience(3);
        $item1->setUrl('testUrl');

        return [$item1];
    }

    private function getExpectedReport(): string
    {
        return
            'Title,URL,Description,Level,"Years of experience"
            testTitle,testUrl,testDescription,testSenior,3';
    }

    private function assertReportContentEquals($expectedContent)
    {
        $content = @file_get_contents(self::REPORT_PATH . '/jobs.csv');
        $this->assertEquals($expectedContent, $content);
    }
}