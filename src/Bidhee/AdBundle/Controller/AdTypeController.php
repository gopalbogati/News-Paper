<?php

namespace Bidhee\AdBundle\Controller;

use Bidhee\AdBundle\Form\AdTypeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdTypeController extends Controller
{
    public function indexAction(Request $request)
    {
        $adTypesListQuery = $this->get('bidhee.ad.type.service')->getAdTypesListQuery($request->query->all());

        $data['adTypes'] = $this->get('bidhee.pagination.service')->getPaginatedList($adTypesListQuery);

        $data['pageTitle'] = 'Ad Type';

        $data['pageDescription'] = 'List';

        return $this->render('BidheeAdBundle:AdType:index.html.twig', $data);
    }

    public function createAction(Request $request)
    {
        $data['pageTitle'] = 'Ad Type';
        $data['pageDescription'] = 'Create';
        $adType = null;

        $successMessage = 'Ad Type added successfully.';
        $adTypeService = $this->get('bidhee.ad.type.service');

        if( $id = $request->get('id') ){
            $adType = $adTypeService->getSpecificAdType( $id );

            if( !$adType )
            {
                return $this->redirectToRoute('bidhee_admin_dashboard');
            }

            $data['pageDescription'] = 'Update';
            $successMessage = 'Ad Type updated successfully';
        }

        $form = $this->createForm(AdTypeType::class, $adType);

        if(  'POST' == $request->getMethod() ){
            $form->handleRequest($request);

            if( $form->isValid() )
            {
                $adType = $form->getData();

                try{
                    $adTypeService->create($adType);
                    $this->addFlash('success', $successMessage);
                    return $this->redirectToRoute('bidhee_admin_ad_type_list');
                }catch( \Exception $e )
                {
                    $data['errorMessage'] = $e->getMessage();
                }
            }
        }

        $data['form'] = $form->createView();


        return $this->render('BidheeAdBundle:AdType:post.html.twig', $data);
    }
}
