<?php

namespace App\Controller;

use App\Services\CrawlerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CrawlerController extends AbstractController
{
    /** @Route("/jobs", name="get_jobs", methods={"GET"}) */
    public function getTest(CrawlerService $crawlerService, string $country)
    {
        return $this->json([$crawlerService->crawlWebsite()]);
    }
}