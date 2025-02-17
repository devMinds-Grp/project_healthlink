<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Repository\CategoryRepository;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    #[Route('/admin/category/reclamation', name: 'app_admin_category_reclamation')]
    public function index5(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }
    #[Route('/admin/reclamation', name: 'app_admin_reclamation')]
    public function index3(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/admin/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }
    #[Route('/admin/reclamation/{id}', name: 'app_admin_reclamation_show')]
    public function show1(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/admin/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
   
}
