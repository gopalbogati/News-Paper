<?php
/**
 * @Author Bhaktaraz Bhatta <bhattabhakta@gmail.com>
 */

namespace Bidhee\GalleryBundle\Controller;

use Bidhee\GalleryBundle\Entity\Album;
use Bidhee\GalleryBundle\Entity\Image;
use Bidhee\GalleryBundle\Form\Type\AlbumType;
use Bidhee\GalleryBundle\Form\Type\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class GalleryController extends Controller
{

    public function listGalleryAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $galleryRepo = $this->container->get('doctrine')->getRepository('BidheeGalleryBundle:Album');
        $data['albums'] = $galleryRepo->findBy(['status' => 1]);
        //$data['albums'] = $this->get('bidhee.pagination.service')->getPaginatedList($albumListQuery);

        $data['page_title'] = 'Gallery';
        $data['page_desc'] = 'List Gallery';

        $form = $this->createForm(new AlbumType(), new Album());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $album = $form->getData();

            $album->setUser($user);
            $album->setStatus(TRUE);
            $em->persist($album);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Album added Successfully.');

            return $this->redirect($this->generateUrl('bidhee_admin_gallery_list'));
        }

        $data['form'] = $form->createView();

        return $this->render('BidheeGalleryBundle:Gallery:list.html.twig', $data);
    }


    public function addGalleryAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $form = $this->createForm(new AlbumType(), new Album());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $album = $form->getData();

            $album->setUser($user);
            $album->setStatus(TRUE);
            $em->persist($album);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Album added successfully.');

            return $this->redirect($this->generateUrl('bidhee_admin_gallery_list'));
        }

        $data['page_title'] = 'Gallery';
        $data['page_desc'] = 'Add Gallery';
        $data['form'] = $form->createView();

        return $this->render('BidheeGalleryBundle:Gallery:add.html.twig', $data);
    }

    public function editGalleryAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $id = $request->get('id');
        if ($id == '') {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $galleryRepo = $this->getDoctrine()->getRepository('BidheeGalleryBundle:Album');
        $gallery = $galleryRepo->find($id);

        if (!$gallery) {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $form = $this->createForm(new AlbumType(), $gallery);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $album = $form->getData();

                $album->setStatus(TRUE);
                $em->persist($album);
                $em->flush();

                return $this->redirect($this->generateUrl('bidhee_admin_gallery_list'));
            }
        }

        $data['page_title'] = 'Gallery';
        $data['page_desc'] = 'Edit Gallery';
        $data['form'] = $form->createView();

        return $this->render('BidheeGalleryBundle:Gallery:add.html.twig', $data);
    }

    public function listImageAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $id = $request->get('id');
        if ($id == '') {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $galleryRepo = $this->getDoctrine()->getRepository('BidheeGalleryBundle:Album');
        $gallery = $galleryRepo->find($id);

        $imageRepo = $this->container->get('doctrine')->getRepository('BidheeGalleryBundle:Image');
        $data['images'] = $imageRepo->findBy(array('album' => $gallery));
        $data['gallery'] = $gallery;

        $form = $this->createForm(new ImageType(), new Image());

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                try {

                    $em = $this->getDoctrine()->getManager();
                    $image = $form->getData();

                    $image->setAlbum($gallery);
                    $image->setStatus(TRUE);

                    //$gallery->setCoverImage($image);

                    $em->persist($image);
                    $em->persist($gallery);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'Image uploaded successfully.');

                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('error', 'Something went wrong!! ' . $e->getMessage());
                }
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Something went wrong!! ' . $this->getErrorMessages($form));
            }

            return $this->redirect($this->generateUrl('bidhee_admin_gallery_image_list', array('id' => $gallery->getId())));
        }

        $data['form'] = $form->createView();

        $data['page_title'] = 'Gallery Images - ' . $gallery->getName();
        $data['page_desc'] = 'List Gallery Images';

        return $this->render('BidheeGalleryBundle:Image:list.html.twig', $data);
    }

    public function addImageAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $id = $request->get('id');

        if ($id == '') {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $galleryRepo = $this->getDoctrine()->getRepository('BidheeGalleryBundle:Album');
        $gallery = $galleryRepo->find($id);

        $form = $this->createForm(new ImageType(), new Image());
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $image = $form->getData();

            $image->setAlbum($gallery);
            $image->setUser($user);
            $image->setStatus(TRUE);

            //$gallery->setCoverImage($image);

            $em->persist($image);
            $em->persist($gallery);
            $em->flush();

            return $this->redirect($this->generateUrl('bidhee_admin_gallery_image_list', array('id' => $gallery->getId())));

        }

        $data['form'] = $form->createView();

        $data['gallery'] = $gallery;
        $data['page_title'] = 'Add Gallery Images - ' . $gallery->getName();
        $data['page_desc'] = 'Add Gallery Images';

        return $this->render('BidheeGalleryBundle:Image:add.html.twig', $data);
    }

    public function deleteGalleryAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $id = $request->get('id');
        if ($id == '') {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $galleryRepo = $this->getDoctrine()->getRepository('BidheeGalleryBundle:Album');
        $gallery = $galleryRepo->find($id);

        if ($gallery) {
            $em = $this->getDoctrine()->getEntityManager();
            $gallery->setStatus(FALSE);
            $em->persist($gallery);
            $em->flush();

            return $this->redirect($this->generateUrl('bidhee_admin_gallery_list', array('id' => $gallery->getId())));
        }
    }

    public function deleteImageAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $id = $request->get('id');
        if ($id == '') {
            return $this->redirect($this->generateUrl('bidhee_admin_dashboard'));
        }

        $imageRepo = $this->getDoctrine()->getRepository('BidheeGalleryBundle:Image');
        $image = $imageRepo->find($id);

        $gallery = $image->getAlbum();

        if ($image) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($image);

            $em->flush();

            return $this->redirect($this->generateUrl('bidhee_admin_gallery_image_list', array('id' => $gallery->getId())));
        }
    }

    private function getErrorMessages(Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }


        $out = '';

        if (count($errors)) {
            foreach ($errors as $err) {
                $out .= ' ' . $err . ' ';
            }
        }

        //return $errors;
        return $out;
    }
}
