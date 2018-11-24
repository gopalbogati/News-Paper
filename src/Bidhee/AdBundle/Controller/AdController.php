<?php

namespace Bidhee\AdBundle\Controller;


use Bidhee\AdBundle\Form\AdType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdController extends Controller
{

    public function indexAction(Request $request)
    {
        $adListQuery = $this->get('bidhee.ad.service')->getAdListQuery($request->query->all());

        $data['ads'] = $this->get('bidhee.pagination.service')->getPaginatedList($adListQuery);

        $data['pageTitle'] = 'Ads';

        $data['pageDescription'] = 'List';

        return $this->render('BidheeAdBundle:Ad:index.html.twig', $data);
    }

    public function createAction(Request $request)
    {
        $data['pageTitle'] = 'Ad';
        $data['pageDescription'] = 'Create';
        $ad = null;

        $successMessage = 'Ad added successfully.';
        $adService = $this->get('bidhee.ad.service');

        if ($id = $request->get('id')) {

            $ad = $adService->getSpecificAd($id);

            if (!$ad) {
                return $this->redirectToRoute('bidhee_admin_dashboard');
            }

            $data['pageDescription'] = 'Update';
            $successMessage = 'Ad updated successfully';
        }

        $form = $this->createForm(AdType::class, $ad);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $ad = $form->getData();
                try {
                    $adService->create($ad);
                    $this->addFlash('success', $successMessage);
                } catch (\Exception $e) {
                    $data['errorMessage'] = $e->getMessage();
                }
            }

            return $this->redirectToRoute('bidhee_admin_ad_list');
        }

        $data['form'] = $form->createView();

        return $this->render('BidheeAdBundle:Ad:post.html.twig', $data);
    }


}
