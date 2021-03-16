<?php

namespace App\Services;

use App\Entity\Job;
use League\Csv\Writer;

class CsvReportGenerator
{
    /**
     * @var string
     */
    private $reportPath;

    public function __construct(string $reportPath)
    {
        $this->reportPath = $reportPath;
    }

    public function process(array $jobs): void
    {
        $writer = Writer::createFromPath($this->reportPath.'/jobs.csv', 'w+');
        $writer->insertOne(['Title', 'URL', 'Description', 'Level', 'Years of experience']);

        /** @var Job $job */
        foreach ($jobs as $job) {
            $writer->insertOne([
                $job->getTitle(),
                $job->getUrl(),
                $job->getDescription(),
                $job->getLevel(),
                $job->getYearsOfExperience(),
            ]);
        }
    }
}