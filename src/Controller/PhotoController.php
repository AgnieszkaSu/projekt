<?php
/**
 * Photo controller.
 */

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Product;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use App\Service\FileUploader;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
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
     * Add new photo.
     *
     * @param Request $request    HTTP request
     * @param PhotoRepository $repository Photo repository
     * @param FileUploader $fileUploader File uploader
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
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

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $photo->getLocation();

            $fileName = $fileUploader->upload($file);
            $photo->setLocation($fileName);

            $repository->save($photo);
            $this->addFlash('success', 'message.created');

            return $this->redirectToRoute('type_view', ['id' => $photo->getProduct()->getType()->getId()]);
        }

        return $this->render(
            'photo/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Add new photo to product.
     *
     * @param Request $request    HTTP request
     * @param PhotoRepository $repository Photo repository
     * @param FileUploader $fileUploader File uploader
     * @param Product $product Product repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
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

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $photo->getLocation();

            $fileName = $fileUploader->upload($file);
            $photo->setLocation($fileName);

            $repository->save($photo);
            $this->addFlash('success', 'message.created');

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

    /**
     * Delete photo.
     *
     * @param Request $request    HTTP request
     * @param PhotoRepository $repository Photo repository
     * @param FileUploader $fileUploader File uploader
     * @param Photo $photo Photo repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/delete/{id}/",
     *     requirements={"id": "0*[1-9]\d*"},
     *     methods={"GET", "DELETE"},
     *     name="photo_delete",
     * )
     *
     * @IsGranted("MANAGE")
     */
    public function delete(Request $request, PhotoRepository $repository, FileUploader $fileUploader, Photo $photo): Response
    {
        $photo->setLocation(new File($fileUploader->getTargetDirectory().'/'.$photo->getLocation()));

        $form = $this->createForm(PhotoType::class, $photo, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($photo);
            $this->addFlash('success', 'message.deleted');

            return $this->redirectToRoute('type_view', ['id' => $photo->getProduct()->getType()->getId()]);
        }

        return $this->render(
            'photo/delete.html.twig',
            [
                'form' => $form->createView(),
                'type_id' => $photo->getProduct()->getType()->getId(),
            ]
        );
    }
}
