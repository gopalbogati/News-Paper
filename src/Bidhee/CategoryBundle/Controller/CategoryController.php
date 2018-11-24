<?php

namespace Bidhee\CategoryBundle\Controller;

use Exception;
use Bidhee\CategoryBundle\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{

    public function indexAction(Request $request)
    {
        $filters = $request->query->all();
        $filters['parentOnly'] = true;
        $categoriesQuery = $this->get('bidhee.category.service')->getCategoriesListQuery($filters);

        $data['categories'] = $this->get('bidhee.pagination.service')->getPaginatedList($categoriesQuery, 15);

        $data['pageTitle'] = 'Category';

        $data['pageDescription'] = 'List';

        return $this->render('BidheeCategoryBundle:Category:index.html.twig', $data);
    }

    public function createAction(Request $request)
    {

        $data['pageDescription'] = 'New';
        $successMessage = 'Category Added Successfully.';
        $categoryService = $this->get('bidhee.category.service');

        $category = null;
        if ($id = $request->get('id')) {
            $category = $categoryService->getCategory($id);

            if (is_null($category)) {
                $this->redirectToRoute('bidhee_admin_dashboard');
            }

            $data['pageDescription'] = 'Update';
            $successMessage = 'Category Updated Successfully.';
        }

        $form = $this->createForm(new CategoryType(), $category);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $category = $form->getData();

                try {
                    $categoryService->create($category);
                    $this->addFlash('success', $successMessage);

                    return $this->redirectToRoute('bidhee_admin_category_list');
                } catch (Exception $e) {
                    $data['errorMessage'] = $e->getMessage();
                }
            }
        }

        $data['form'] = $form->createView();
        $data['pageTitle'] = 'Category';

        return $this->render('BidheeCategoryBundle:Category:post.html.twig', $data);
    }

}
