<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Image;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/product')]
class ProductAdminController extends AbstractController
{
    #[Route('/', name: 'app_product_admin_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product_admin/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload d'image
            $imageFile = $form->get('imageFile')->getData();
            
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $image = new Image();
                $image->setUrl($imageFileName);
                $product->addImage($image);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Le produit a été créé avec succès !');

            return $this->redirectToRoute('app_product_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product_admin/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload d'image
            $imageFile = $form->get('imageFile')->getData();
            
            if ($imageFile) {
                // Supprimer l'ancienne image si elle existe
                if (count($product->getImages()) > 0) {
                    foreach($product->getImages() as $image) {
                        $oldImagePath = $fileUploader->getTargetDirectory() . '/' . $image->getUrl();
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                        $entityManager->remove($image);
                    }
                }
                
                $imageFileName = $fileUploader->upload($imageFile);
                $image = new Image();
                $image->setUrl($imageFileName);
                $product->addImage($image);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Le produit a été modifié avec succès !');

            return $this->redirectToRoute('app_product_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('product_admin/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->getPayload()->getString('_token'))) {
            // Supprimer l'image si elle existe
            if (count($product->getImages()) > 0) {
                foreach($product->getImages() as $image) {
                    $imagePath = $fileUploader->getTargetDirectory() . '/' . $image->getUrl();
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('success', 'Le produit a été supprimé avec succès !');
        }

        return $this->redirectToRoute('app_product_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
