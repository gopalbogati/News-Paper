<?php

namespace Bidhee\UserBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Class LoginSuccessHandler
 * @author Deepak Pandey
 */
class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $router = $this->container->get('router');
        $userAuthorizationService = $this->container->get('security.authorization_checker');

        if ($userAuthorizationService->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($router->generate('bidhee_admin_dashboard'), 307);
        }

        if ($userAuthorizationService->isGranted('ROLE_CONSUMER')) {
            return new RedirectResponse($router->generate('bidhee_frontend_homepage'), 307);
        }

        return new RedirectResponse($router->generate('bidhee_frontend_homepage'), 307);
    }
}
