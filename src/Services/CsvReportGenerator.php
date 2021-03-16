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
        $fileName = sprintf($this->reportPath.'/jobs%s.csv', date('Ymdhis'));
        $writer = Writer::createFromPath($fileName, 'w+');
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