<?php

namespace App\Controller;

use App\FeedGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    private $feedGenerator;

    public function __construct(FeedGenerator $feedGenerator)
    {
        $this->feedGenerator = $feedGenerator;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $feed = $this->feedGenerator->generateFeed();

        return new Response($feed->generateFeed());
    }
}
