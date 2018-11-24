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
 * Class InstantArticleFeedService
 * @author Deepak Pandey <er.pandeydip@gmail.com>
 */
class InstantArticleFeedService
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
                ->content($this->createContent($newsItem))
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

    private function createContent(array $news)
    {
        $authorName = (empty($news['authorName'] || is_null($news['authorName']))) ? 'नागरिक' : $news['authorName'];
        $template = '<!doctype html>
<html lang="en" prefix="op: http://media.facebook.com/op#">
<head><meta charset="utf-8"/><meta property="op:markup_version" content="v1.0"><link rel="canonical" href="' . $this->getNewsPath($news) . '"><title>' . $news['title'] . '</title>
<meta property="fb:article_style" content="default">
</head>
<body>
<article>
    <header>';
        if (!is_null($news['featuredImage'])) {
            $template .= '<figure><img src="' . $news['featuredImage'] . '"/>';
            if (!empty($news['caption'])) {
                $template .= '<figcaption>' . $news['caption'] . '</figcaption>';
            }
            $template .= '</figure>';
        }

        $template .= '<h1>' . $news['title'] . '</h1>';

        $template .= '<address>' . $authorName . '</address>';
        $template .= '<time class="op-published" dateTime="' . $news['publishOn']->format('Y-m-d\TH:i:s\Z') . '">' . $this->formatDate($news['publishOn']) . '</time>';
        $template .= '<time class="op-modified" dateTime="' . $news['publishOn']->format('Y-m-d\TH:i:s\Z') . '">' . $this->formatDate($news['publishOn']) . '</time>';
        $template .= '</header>' . $news['content'];
        $template .= '<figure class="op-tracker"><iframe><script>';
        $template .= "(function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-31714015-1', 'auto');
    ga('send', 'pageview');";
        $template .= '</script></iframe></figure>';
        $template .= '</article>';
        $template .= '</body></html>';

        return $template;
    }

    private function formatDate($date)
    {
        return $this->container->get('bidhee.twig_extension.nepali_date')->nepaliDateTimeFilter($date->format('Y-m-d H:i:s'));
    }
}
