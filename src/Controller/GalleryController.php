<?php

namespace App\Controller;

use App\Entity\Image;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    /**
     * @Route("/", name="image_list")
     * @Method({"GET"})
     */
    public function index()
    {
        $images = $this->getDoctrine()
            ->getRepository(Image::class)
            ->findAll();

        return $this->render('gallery/index.html.twig', [
            "images" => $images,
        ]);
    }

    /**
     * @Route("/image/{:id}", name="image_show")
     * @Method({"GET"})
     */
    public function show($id)
    {
        $image = $this->getDoctrine()
            ->getRepository(Image::class)
            ->find($id);

        return $this->render('gallery/image.html.twig', [
            "image" => $image
        ]);
    }
    

    /**
     * @Route("/gallery/save", name="save")
     */
    public function save()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $image = new Image();
        $image->setName('Image name');
        $image->setPath('https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__340.jpg');

        $entityManager->persist($image);
        $entityManager->flush();

        return new Response('saved!');
    }
}