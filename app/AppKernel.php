<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Dmishh\SettingsBundle\DmishhSettingsBundle;

class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

            new PUGX\MultiUserBundle\PUGXMultiUserBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new FM\ElfinderBundle\FMElfinderBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\AopBundle\JMSAopBundle(),
            new \JMS\SerializerBundle\JMSSerializerBundle(),
            new Dmishh\Bundle\SettingsBundle\DmishhSettingsBundle,
            new RMS\PushNotificationsBundle\RMSPushNotificationsBundle(),

            new Bidhee\UserBundle\BidheeUserBundle(),
            new Bidhee\AdminBundle\BidheeAdminBundle(),
            new Bidhee\FrontendBundle\BidheeFrontendBundle(),
            new Bidhee\CategoryBundle\BidheeCategoryBundle(),
            new Bidhee\FoundationBundle\BidheeFoundationBundle(),
            new Bidhee\NewsBundle\BidheeNewsBundle(),
            new Bidhee\AdBundle\BidheeAdBundle(),
            new Bidhee\ContentBundle\BidheeContentBundle(),
            new Bidhee\FeedBundle\BidheeFeedBundle(),
            new Bidhee\GalleryBundle\BidheeGalleryBundle(),
            new Bidhee\EpaperBundle\EpaperBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
