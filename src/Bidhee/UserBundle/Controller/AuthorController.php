<?php

namespace Bidhee\UserBundle\Controller;

use Bidhee\UserBundle\Entity\Author;
use Bidhee\UserBundle\Entity\User;
use Bidhee\UserBundle\Form\AuthorType;
use Bidhee\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AuthorController extends Controller
{

    public function indexAction(Request $request)
    {
       $this->denyAccessUnlessGranted('ROLE_USER_MANAGER');

        $authorListQuery = $this->get('bidhee.author.service')->getAuthorListQuery($request->query->all());

        $data['authors'] = $this->get('bidhee.pagination.service')->getPaginatedList($authorListQuery);

        $data['pageTitle'] = 'Author';
        $data['pageDescription'] = 'List';

        return $this->render('BidheeUserBundle:Author:index.html.twig', $data);
    }

    public function createAction(Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_USER_MANAGER');

        $form = $this->createForm(new AuthorType(), new Author());

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $formData = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($formData);

                try {
                    $em->flush();
                    $this->addFlash('success', 'Author Added Successfully.');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Unable to add author.' . $e->getMessage());
                }

                return $this->redirectToRoute('bidhee_admin_author_list');
            }
        }

        $data['form'] = $form->createView();
        $data['pageTitle'] = 'Author';
        $data['pageDescription'] = 'Add';

        return $this->render('BidheeUserBundle:Author:post.html.twig', $data);
    }

    public function updateAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER_MANAGER');

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $authorRepo = $em->getRepository('BidheeUserBundle:Author');
        $author = $authorRepo->find($id);

        if (!$author) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(new AuthorType(), $author);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($formData);

            try {
                $em->flush();

                $this->addFlash('success', 'Author updated successfully.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Unable to update author.');
            }

            return $this->redirectToRoute('bidhee_admin_author_list');
        }

        $data['formType'] = 'update';
        $data['form'] = $form->createView();
        $data['pageTitle'] = 'Author';
        $data['pageDescription'] = 'update';

        return $this->render('BidheeUserBundle:Author:post.html.twig', $data);
    }
}
