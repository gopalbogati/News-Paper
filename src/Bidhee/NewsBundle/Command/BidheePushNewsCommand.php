<?php
/**
 * @Author Bhaktaraz Bhatta <bhattabhakta@gmail.com>
 */

namespace Bidhee\NewsBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use RMS\PushNotificationsBundle\Message\iOSMessage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use RMS\PushNotificationsBundle\Message\AndroidMessage;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class BidheePushNewsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bidhee:push-news-command')
            ->setDescription('Push News notifications');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get notification to be sent here (A notification is created whenever a News is created)

        // Get consumers device ID from consumer server

        // Loop through consumer and Enqueue all the notification from here (Bidhee\Jobs\NotificationJob)
        if ('device' == 'IOS') { // For example
            $message = new iOSMessage();
            $message->setAPSSound('default');
            $message->setMessage('Message text');
            $message->setDeviceIdentifier('Device ID');
        }
        if ('device' == 'ANDROID') { // For example
            $message = new AndroidMessage();
            $message->setAPSSound('default');
            $message->setMessage('Message text');
            $message->setDeviceIdentifier('Device ID');
        }

        // Revome this from here and add it in NotificationJob
        $this->getContainer()->get('rms_push_notifications')->send($message);

        // Set the notification pushed flag to true after success

        $output->writeln('News has been pushed successfully.');
    }

}
