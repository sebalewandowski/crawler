<?php

namespace App\Services;

use App\Entity\Job;
use League\Csv\Writer;

class CsvReportGenerator
{
    public function __construct()
    {
    }

    public function process(array $jobs): void
    {
        $fileName = sprintf('jobs%s.csv', date('Ymdhis'));
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