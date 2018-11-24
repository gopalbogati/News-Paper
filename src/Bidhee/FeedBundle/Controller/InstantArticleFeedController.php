<?php

namespace Bidhee\FeedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class FeedController
 * @author Deepak Pandey <er.pandeydip@gmail.com>
 */
class InstantArticleFeedController extends Controller
{

    public function indexAction()
    {
        $feed = $this->container->get('bidhee.instant_article_feed.service')->getFeeds();

        return $this->render('@BidheeFeed/Feed/index.html.twig', compact('feed'));
    }
}
