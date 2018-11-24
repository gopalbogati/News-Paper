<?php

namespace Bidhee\FeedBundle\Service;

use DateTime;
use Doctrine\ORM\EntityManager;
use Bhaktaraz\RSSGenerator\Feed;
use Bhaktaraz\RSSGenerator\Item;
use Bhaktaraz\RSSGenerator\Channel;
use Bidhee\NewsBundle\Entity\News;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class FeedService
 * @author Deepak Pandey <er.pandeydip@gmail.com>
 */
class FeedService
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * @return Feed
     */
    public function getFeeds()
    {
        $news = $this->em->getRepository(News::class)->newsWithIn24Hours();

        return $this->convertToXml($news);
    }

    /**
     * @param array $news
     * @return Feed
     */
    private function convertToXml(array $news)
    {
        $feed = new Feed();
        $channel = $this->getFeedChannel($feed);
        $this->addItemsToFeed($channel, $news);

        return $feed;
    }

    /**
     * Create Feed Channel
     *
     * @param $feed
     * @return Channel
     */
    private function getFeedChannel($feed)
    {
        $date = new DateTime();
        $channel = new Channel();
        $channel->title('Bidhee News')
            ->description('Bidhee News - The Most Comprehensive No. 1 News Portal of Nepal - Breaking News, Latest, Politics, World,
        Economy, Entertainment, Sports, Technology, Blog, Cartoon, Opinion, Science, Interview, Health, Photo
        Feature')
            ->url($this->generateBasePath())
            ->language('en-US')
            ->copyright('Copyright ' . date('Y'), 'Bidhee News')
            ->updateFrequency(1)
            ->updatePeriod('hourly')
            ->pubDate($date->getTimestamp())
            ->appendTo($feed);

        return $channel;
    }

    /**
     * Add Items to Feeds
     *
     * @param Channel $channel
     * @param array $news
     */
    private function addItemsToFeed(Channel $channel, array $news)
    {
        foreach ($news as $newsItem) {
            $item = new Item();
            $item->title($newsItem['title'])
                ->creator($newsItem['authorName'])
                ->description($newsItem['introText'])
                ->content($newsItem['content'])
                ->url($this->getNewsPath($newsItem))
                ->pubDate($newsItem['publishOn']->getTimestamp())
                ->guid($this->getNewsPath($newsItem))
                ->appendTo($channel);
        }
    }

    /**
     * Generate News Detail path
     *
     * @param $newsItem
     * @return string
     */
    private function getNewsPath($newsItem)
    {
        $baseUrl = $this->generateBasePath();
        $routePath = $this->container->get('router')->generate('bidhee_frontend_news',
            ['id' => $newsItem['id']]);

        return $baseUrl . $routePath;
    }

    /**
     * @return string
     */
    private function generateBasePath()
    {
        $request = $this->container->get('request');

        return $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
    }
}
