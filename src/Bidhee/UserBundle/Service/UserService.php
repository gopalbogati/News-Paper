<?php

namespace Bidhee\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Bidhee\UserBundle\Entity\User;

/**
 * Class UserService
 * @package Bidhee\UserBundle\Service
 * @author Danepliz
 */
class UserService
{

    protected $em;

    protected $userManager;

    /**
     * UserService constructor.
     * @param EntityManager $entityManager
     * @param $userManager < fos_user.user_manager service >
     */
    public function __construct(EntityManager $entityManager, $userManager)
    {
        $this->em = $entityManager;
        $this->userManager = $userManager;
    }

    public function getRoleDescription(User $user)
    {
        $roles = $user->getRoles();

        return ( isset($roles[0]) )? User::$definedRoles[$roles[0]] : '';
    }

    public function getRole(User $user)
    {
        $roles = $user->getRoles();

        return ( isset($roles[0]) )? $roles[0] : '';
    }

    public function getUserById($id)
    {
        return $this->userManager->findUserBy(array('id'=>$id));
    }

    public function updateUser(User $user)
    {
        $this->userManager->updateUser($user);
        $this->em->persist($user);
        $this->em->flush();
    }


}