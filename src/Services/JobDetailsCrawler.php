<?php

namespace App\Services;

use App\Entity\Job;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class JobDetailsCrawler
{
    const SPOTIFY_URI = 'https://www.spotifyjobs.com/jobs/';

    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param Job $job
     * @return Job
     */
    public function getJobDetails(Job $job): Job
    {
        /** @var Crawler $crawler */
        $crawler = $this->client->request('GET', self::SPOTIFY_URI . $job->getUrl());

        $jobDescription = $crawler->filter('.list_list__mHc5U')->html();
        $jobRequirements = $crawler->filter('.list_list__mHc5U')->eq(1)->html();

        $job->setDescription(trim(strip_tags($jobDescription)));
        $job->setRequirements(trim(strip_tags($jobRequirements)));

        return $job;
    }
}