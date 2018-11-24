<?php

namespace Bidhee\ContentBundle\Controller;

use Bidhee\ContentBundle\Entity\Content;
use Bidhee\ContentBundle\Form\ContentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContentController extends Controller
{

    public function indexAction(Request $request)
    {
        $offset = $request->query->get('page', 1);
        $perPage = $this->container->getParameter('per_page_limit');

        $em = $this->getDoctrine()->getManager();
        $contentRepo = $em->getRepository('BidheeContentBundle:Content');
        $contents = $contentRepo->getAll($offset, $perPage, $filters = []);

        $data['contents'] = $contents;
        $data['pageTitle'] = 'Content';
        $data['pageDescription'] = 'List';

        return $this->render('BidheeContentBundle:Content:index.html.twig', $data);
    }

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new ContentType(), new Content());

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $content = $form->getData();
                $em->persist($content);

                try {
                    $em->flush();
                    $this->addFlash('success', 'Content added successfully');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Unable to add content. ' . $e->getMessage());
                }
            }

            return $this->redirectToRoute('bidhee_admin_content_index');
        }

        $data['form'] = $form->createView();
        $data['pageTitle'] = 'Content';
        $data['pageDescription'] = 'New';

        return $this->render('BidheeContentBundle:Content:create.html.twig', $data);
    }

    public function editAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $contentRepo = $em->getRepository('BidheeContentBundle:Content');
        $content = $contentRepo->find($id);

        $form = $this->createForm(new ContentType(), $content);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $contentForm = $form->getData();
                $em->persist($contentForm);

                try {
                    $em->flush();
                    $this->addFlash('success', 'Content updated successfully');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Unable to update content. ' . $e->getMessage());
                }
            }

            return $this->redirectToRoute('bidhee_admin_content_index');
        }

        $data['form'] = $form->createView();
        $data['content'] = $content;
        $data['pageTitle'] = 'Content';
        $data['pageDescription'] = 'Edit';

        return $this->render('BidheeContentBundle:Content:create.html.twig', $data);
    }

    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $contentRepo = $em->getRepository('BidheeContentBundle:Content');
        $content = $contentRepo->find($id);

        try {
            $em->remove($content);
            $em->flush();
            $this->addFlash('success', 'Content removed successfully');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Unable to delete content. ' . $e->getMessage());
        }

        return $this->redirectToRoute('bidhee_admin_content_index');
    }
}
