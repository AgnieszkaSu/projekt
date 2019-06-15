<?php
/**
 * Photo controller.
 */

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Product;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class PhotoController.
 *
 * @Route("/photo")
 */
class PhotoController extends AbstractController
{
    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\PhotoRepository           $repository Photo repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new/",
     *     methods={"GET", "POST"},
     *     name="photo_new",
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function new(Request $request, PhotoRepository $repository, FileUploader $fileUploader): Response
    {
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);
        dump($form);
        dump($request->files);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($photo);
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $photo->getLocation();

            $fileName = $fileUploader->upload($file);
            $photo->setLocation($fileName);

            $repository->save($photo);
            $this->addFlash('success', 'Created successfully');
            dump($photo);

            // return $this->redirectToRoute('type_view', ['id' => $photo->getProduct()->getType()->getId()]);
        }

        return $this->render(
            'photo/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * New with product id action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\PhotoRepository           $repository Photo repository
     * @param \App\Entity\Product                       $product Product repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new/{id}/",
     *     requirements={"id": "0*[1-9]\d*"},
     *     methods={"GET", "POST"},
     *     name="photo_new_with_id",
     * )
     * @ParamConverter("id", class="App\Entity\Product", options={"id": "id"})
     *
     * @IsGranted("MANAGE")
     */
    public function new_with_id(Request $request, PhotoRepository $repository, FileUploader $fileUploader, Product $product): Response
    {
        $photo = new Photo();
        $photo->setProduct($product);
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);
        dump($form);
        dump($request->files);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($photo);
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $photo->getLocation();

            $fileName = $fileUploader->upload($file);
            $photo->setLocation($fileName);

            $repository->save($photo);
            $this->addFlash('success', 'Created successfully');
            dump($photo);

            return $this->redirectToRoute('type_view', ['id' => $photo->getProduct()->getType()->getId()]);
        }

        return $this->render(
            'photo/new_with_id.html.twig',
            [
                'form' => $form->createView(),
                'type_id' => $photo->getProduct()->getType()->getId(),
            ]
        );
    }
}
