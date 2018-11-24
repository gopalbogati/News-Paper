<?php

/*
 * @Author Bhaktaraz Bhatta <bhattabhakta@gmail.com>
 */

namespace Bidhee\NewsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BidheePublishNewsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('bidhee:publish-news-command')
            ->setDescription('Publish scheduled news');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $newsRepo = $em->getRepository('BidheeNewsBundle:News');

        $scheduledNews = $newsRepo->getScheduledNews();

        $count = 0;
        foreach ($scheduledNews as $news) {
            $news->setPublished(true);
            $em->persist($news);

            $output->writeln('Scheduled news ' . $news->getTitle() . ' is now published.');
            $count++;
        }

        $em->flush();

        $output->writeln($count . ' scheduled news has been published successfully.');
    }

}
