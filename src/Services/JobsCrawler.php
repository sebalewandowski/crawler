<?php

namespace App\Services;

use App\Entity\Job;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JobsCrawler
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * JobsCrawler constructor.
     *
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return array
     * @throws DecodingExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public function getJobs(): array
    {
        $jobs = [];

        foreach ($this->getJobsRequest() as $response) {
            foreach ($response as $responseJob) {
                $job = new Job();
                $job->setUrl($responseJob['id']);
                $job->setTitle($responseJob['text']);
                $job->setCategory($responseJob['main_category']['name']);
                $job->setSubCategory($responseJob['sub_category']['name']);

                $jobs[] = $job;
            }
        }

        return $jobs;
    }

    /**
     * @return \Generator
     * @throws DecodingExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    private function getJobsRequest(): \Generator
    {
        $found = 99;
        $perPage = 99;
        $pageNr = 1;

        while ($found === $perPage) {
            $response = $this->httpClient->request(
                'GET',
                'https://api-dot-new-spotifyjobs-com.nw.r.appspot.com/wp-json/animal/v1/job/search?q=&l=stockholm&c=&j=');
            $responseArray = $response->toArray()['result'];
            $found = count($responseArray);
            $pageNr++;

            yield $responseArray;
        }
    }
}