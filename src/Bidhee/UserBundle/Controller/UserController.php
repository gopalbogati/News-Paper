<?php

namespace Bidhee\UserBundle\Controller;

use Bidhee\UserBundle\Entity\User;
use Bidhee\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends Controller
{
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER_MANAGER');

        $data['users'] = $this->getDoctrine()->getRepository('BidheeUserBundle:User')->findAll();
        $data['pageTitle'] = 'User';
        $data['pageDescription'] = 'List';
        return $this->render('BidheeUserBundle:User:index.html.twig', $data);
    }

    public function createAction(Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_USER_MANAGER');

        $form = $this->createForm(new UserType(), new User());

        if( 'POST' == $request->getMethod() )
        {
            $form->handleRequest( $request );

            if( $form->isValid() )
            {
                $userData = $form->getData();

                $userManager = $this->get('fos_user.user_manager');
                $user = $userManager->createUser();

                $user->setName($userData->getName());
                $user->setUsername($userData->getUsername());
                $user->setEmail($userData->getEmail());
                $user->setPlainPassword($userData->getPlainPassword());

                $user->setPhone1($userData->getPhone1());
                $user->setPhone2($userData->getPhone2());
                $user->setAddress1($userData->getAddress1());
                $user->setAddress2($userData->getAddress2());
                $user->setDescription($userData->getDescription());

                $user->addRole($userData->getUserGroup());
                $user->setEnabled(true);

                $userManager->updateUser($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);

                try{
                    $em->flush();
                    $this->addFlash('success', 'User Added Successfully.');
                    return $this->redirectToRoute('bidhee_admin_users_list');
                }catch(\Exception $e){
                    $data['errorMessage'] = $e->getMessage();
                }
            }
        }


        $data['form'] = $form->createView();
        $data['pageTitle'] = 'User';
        $data['pageDescription'] = 'Add';
        return $this->render('BidheeUserBundle:User:post.html.twig', $data);
    }

    public function updateAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER_MANAGER');

        $userId = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $userService = $this->get('bidhee.user.service');

        if( ! ( $user = $userService->getUserById($userId) ) )
        {
            throw new NotFoundHttpException();
        }

        $isSuperAdmin = $user->hasRole('ROLE_SUPER_ADMIN');

        $userForm = $this->createForm(
            new UserType(),
            $user,
            [
                'update'  => TRUE,
                'role' => $this->get('bidhee.user.service')->getRole($user),
                'isSuperAdmin' => $isSuperAdmin
            ]
        );
        $userForm->handleRequest($request);

        if ($userForm->isValid())
        {
            $userData = $userForm->getData();

            $user->setName($userData->getName());

            $user->setPhone1($userData->getPhone1());
            $user->setPhone2($userData->getPhone2());
            $user->setAddress1($userData->getAddress1());
            $user->setAddress2($userData->getAddress2());
            $user->setDescription($userData->getDescription());


            if( ! $isSuperAdmin )
            {
                $user->setRoles([$userData->getUserGroup()]);
            }

            try{
                $userService->updateUser($user);

                $this->addFlash('success', 'User updated successfully.');
                return $this->redirectToRoute('bidhee_admin_users_list');
            }catch (\Exception $e){
                $data['errorMessage'] = $e->getMessage();
            }
        }

        $data['formType'] = 'update';
        $data['form'] = $userForm->createView();
        $data['pageTitle'] = 'User';
        $data['pageDescription'] = 'update';

        return $this->render('BidheeUserBundle:User:post.html.twig', $data);
    }

    public function changePasswordAction(Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_USER_MANAGER');

        $userId = $request->get('id');

        $userService = $this->get('bidhee.user.service');

        if( ! ($user = $userService->getUserById($userId) ) )
        {
            throw new NotFoundHttpException();
        }

        $form = $this->createFormBuilder([])
            ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'first_options' => array('label' => 'Password', 'attr'=> ['class'=>'form-control']),
                    'second_options' => array('label' => 'Confirm Password', 'attr'=> ['class'=>'form-control']),
                    'invalid_message' => 'Password did not match',
                )
            )
            ->getForm();

        if( 'POST' == $request->getMethod() )
        {
            $form->handleRequest($request);

            if( $form->isValid() )
            {
                $formData = $form->getData();

                $user->setPlainPassword($formData['plainPassword']);

                try{
                    $userService->updateUser($user);
                    $this->addFlash('success', 'Password Changed Successfully.');
                    return $this->redirectToRoute('bidhee_admin_users_list');
                }catch(\Exception $e)
                {
                    $data['errorMessage'] = $e->getMessage();
                }
            }
        }

        $data['form'] = $form->createView();
        $data['pageTitle'] = 'User';
        $data['pageDescription'] = 'Change Password for '.$user->getName();
        return $this->render('BidheeUserBundle:User:change_password.html.twig', $data);
    }


}
