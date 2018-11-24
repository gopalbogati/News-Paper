<?php

namespace Bidhee\EpaperBundle\Controller;


use Bidhee\UserBundle\Entity\Author;
use Bidhee\EpaperBundle\Entity\Epaper;
use Bidhee\EpaperBundle\Form\EpaperType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EpaperController extends Controller
{

    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER_MANAGER');

        $epaperListQuery = $this->get('bidhee.epaper.service')->getEpaperListQuery($request->query->all());

        $data['epapers'] = $this->get('bidhee.pagination.service')->getPaginatedList($epaperListQuery);

        $data['pageTitle'] = 'Epaper';
        $data['pageDescription'] = 'List';

        return $this->render('EpaperBundle:Epaper:index.html.twig', $data);
    }

    public function createAction(Request $request)
    {

        $this->denyAccessUnlessGranted('ROLE_USER_MANAGER');

        $form = $this->createForm(new EpaperType(), new Epaper());

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

                return $this->redirectToRoute('bidhee_admin_epaper_list');
            }
        }

        $data['form'] = $form->createView();
        $data['pageTitle'] = 'Epaper';
        $data['pageDescription'] = 'Add';

        return $this->render('EpaperBundle:Epaper:post.html.twig', $data);
    }

    public function updateAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER_MANAGER');

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $epaperRepo = $em->getRepository('EpaperBundle:Epaper');
        $epaper = $epaperRepo->find($id);

        if (!$epaper) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(new EpaperType(), $epaper);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($formData);

            try {
                $em->flush();

                $this->addFlash('success', 'Author updated successfully.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Unable to update epaper.');
            }

            return $this->redirectToRoute('bidhee_admin_epaper_list');
        }

        $data['formType'] = 'update';
        $data['form'] = $form->createView();
        $data['pageTitle'] = 'Epaper';
        $data['pageDescription'] = 'update';

        return $this->render('EpaperBundle:Epaper:post.html.twig', $data);
    }


    public function deleteAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $epaperRepo = $em->getRepository('EpaperBundle:Epaper');

        $epaper = $epaperRepo->find($id);

        if (!$epaper) {
            $this->addFlash('error', 'Epaper not found with id ' . $id);

            return $this->redirectToRoute('bidhee_admin_epaper_list');
        }

        $em->remove($epaper);

        try {
            $em->flush();
            $this->addFlash('success', 'Epaper permanently deleted successfully');
        } catch (Exception $e) {
            $this->addFlash('error', 'Unable to delete Epaper permanently');
        }

        return $this->redirectToRoute('bidhee_admin_epaper_list');
    }
}

