<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeContentCommand extends Command
{
    const CACHE_KEY = 'CNTP:';
    const PATH_TO_JSON_FILE = 'uploads/';

    protected static $defaultName = 'dump:content-page';

    public function __construct(
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Dumps content page to json file.')
            ->addArgument('slug', InputArgument::REQUIRED, 'slug of searched content page');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $html = file_get_contents('https://www.spotifyjobs.com/jobs');
        $crawler = new Crawler($html);
        dump($crawler);
//        $slug = $input->getArgument('slug');
//
//        /** @var ContentPage $contentPage */
//        $contentPage = $this->cacheService->get(static::CACHE_KEY . $slug);
//
//        $title = strtolower(str_replace(' ', '-', $contentPage->getTitle()));
//
//        $fp = fopen(static::PATH_TO_JSON_FILE . $title . '.json', 'w');
//        fwrite($fp, json_encode($contentPage, true));
//        fclose($fp);
//
        $io->writeln('DONE');

        return Command::SUCCESS;
    }
}