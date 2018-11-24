<?php

namespace Bidhee\FoundationBundle\Twig\Extension;


use Twig_Extension;
use Twig_SimpleFunction;
use Bidhee\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BidheeTemplateExtension extends Twig_Extension
{

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('no_results', [$this, 'noResults']),
            new Twig_SimpleFunction('status_info', [$this, 'statusInfo']),
            new Twig_SimpleFunction('user_role', [$this, 'role']),
        ];
    }

    public function noResults($message)
    {
        $out = '<div class="alert alert-no-results noAutoHide"><i class="fa fa-exclamation-triangle"></i> ' . $message . '</div>';

        return $out;
    }

    public function statusInfo($status, $callAjax = false, $entity = '', $id = '', $property = '')
    {

        $class = ($status) ? 'label-success' : 'label-warning';
        $label = ($status) ? 'YES' : 'NO';
        $out = '<span class="label ' . $class . '">' . $label . '</span>';

        if ($callAjax) {
            $html = '<a href="#" data-toggle="myAjax" data-entity="' . $entity . '" data-id="' . $id . '"';
            $html .= ' data-status = "' . $status . '" data-property="' . $property . '">';
            $html .= $out;
            $html .= '</a>';
        } else {
            $html = $out;
        }


        return $html;
    }


    public function role($roles = [])
    {
        $definedRoles = User::$definedRoles;

        return (isset($roles[0])) ? $definedRoles[$roles[0]] : '';
    }

    public function getName()
    {
        return 'bidhee_template';
    }
}
