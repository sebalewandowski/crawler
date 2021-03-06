<?php

namespace App\Command;

use App\Entity\Job;
use App\Services\CsvReportGenerator;
use App\Services\JobDetailsDetector;
use App\Services\JobDetailsCrawler;
use App\Services\JobsCrawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SpotifyScraperCommand extends Command
{
    protected static $defaultName = 'run:spotify';

    /**
     * @var JobsCrawler
     */
    private JobsCrawler $jobCollector;
    /**
     * @var JobDetailsCrawler
     */
    private JobDetailsCrawler $jobScraper;
    /**
     * @var JobDetailsDetector
     */
    private JobDetailsDetector $jobDetailGuesser;
    /**
     * @var CsvReportGenerator
     */
    private CsvReportGenerator $csvReportGenerator;

    /**
     * SpotifyScraperCommand constructor.
     *
     * @param JobsCrawler $jobCollector
     * @param JobDetailsCrawler $jobScraper
     * @param JobDetailsDetector $jobDetailGuesser
     * @param CsvReportGenerator $csvReportGenerator
     */
    public function __construct(
        JobsCrawler $jobCollector,
        JobDetailsCrawler $jobScraper,
        JobDetailsDetector $jobDetailGuesser,
        CsvReportGenerator $csvReportGenerator
    )
    {
        $this->jobCollector = $jobCollector;
        $this->jobScraper = $jobScraper;
        $this->jobDetailGuesser = $jobDetailGuesser;
        $this->csvReportGenerator = $csvReportGenerator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Collects job information from spotify');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jobs = $this->getJobs($output);
        $this->crawlJobDetails($jobs, $output);
        $this->saveResultsToCsv($jobs, $output);

        return Command::SUCCESS;
    }

    /**
     * @param OutputInterface $output
     * @return array
     */
    private function getJobs(OutputInterface $output): array
    {
        $section = $output->section();
        $progress = new ProgressBar($section);
        $progress->setFormat('<comment>Collecting jobs...</comment> [%bar%] %percent%%');
        $progress->start(100);

        $jobs = $this->jobCollector->getJobs();

        $progress->finish();
        $output->writeln('');
        $output->writeln('<info>Done!</info>');

        return $jobs;
    }

    /**
     * @param array $jobs
     * @param OutputInterface $output
     */
    private function crawlJobDetails(array $jobs, OutputInterface $output): int
    {
        $section = $output->section();
        $progress = new ProgressBar($section);
        $progress->setFormat('<comment>Scraping and processing jobs...</comment> [%bar%] %percent%%');
        $progress->start(count($jobs));

        /** @var Job $job */
        foreach ($jobs as $job) {
            $this->jobScraper->getJobDetails($job);
            $this->jobDetailGuesser->detectJobDetails($job);

            $progress->advance(1);
        }

        $progress->finish();
        $output->writeln('');
        $output->writeln('<info>Done!</info>');

        return Command::SUCCESS;
    }

    private function saveResultsToCsv(array $jobs, OutputInterface $output)
    {
        $section = $output->section();
        $progress = new ProgressBar($section);
        $progress->setFormat('<comment>Generating CSV...</comment> [%bar%] %percent%%');
        $progress->start(100);

        $this->csvReportGenerator->process($jobs);

        $progress->finish();
        $output->writeln('');
        $output->writeln('<info>Done! New file with results created: </info>');
    }
}