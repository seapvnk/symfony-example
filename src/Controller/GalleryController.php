<?php

namespace App\Controller;

use App\Entity\Image;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class GalleryController extends AbstractController
{
    /**
     * @Route("/", name="image_list")
     * @Method({"GET"})
     */
    public function index(): Response
    {
        $images = $this->getDoctrine()
            ->getRepository(Image::class)
            ->findAll();

        return $this->render('gallery/index.html.twig', [
            "images" => $images,
        ]);
    }

    /**
     * @Route("/image/{id}", name="image_show")
     * @Method({"GET"})
     */
    public function show($id): Response
    {
        $image = $this->getDoctrine()
            ->getRepository(Image::class)
            ->find($id);

            return $this->render('gallery/image.html.twig', [
            "image" => $image
        ]);
    }

    /**
     * @Route("/gallery/new", name="image_new")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $image = new Image();
        
        $form  = $this->createFormBuilder($image)
            ->add('name', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('path', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-primary mt-3']])
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->getData();
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($image);
            $entityManager->flush();

            return $this->redirectToRoute('image_list');
        }

        return $this->render('gallery/create.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/gallery/save", name="save")
     */
    public function save(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $image = new Image();
        $image->setName('Image name');
        $image->setPath('https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__340.jpg');

        $entityManager->persist($image);
        $entityManager->flush();

        return new Response('saved!');
    }

    /**
     * @Route("/image/delete", name="delete")
     * @Method({"GET"})
     */
    public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Image::class);
        $image = $repository->find($id);

        $entityManager->remove($image);
        $entityManager->flush();

        return $this->redirectToRoute('image_list');
    }

}