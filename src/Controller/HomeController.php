<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User; // Import the User entity
use App\Entity\Role; // Import the Role entity
use Doctrine\ORM\EntityManagerInterface; // Import EntityManagerInterface

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/doctors', name: 'app_doctor_list')]
    public function listDoctors(EntityManagerInterface $entityManager): Response
    {
        // Fetch the role entity for doctors (assuming the role name is "ROLE_MEDECIN")
        $role = $entityManager->getRepository(Role::class)->findOneBy(['nom' => 'MEDECIN']);

        // Fetch the list of doctors based on the role
        $doctors = $entityManager->getRepository(User::class)->findBy(['role' => $role]);

        // Pass the list of doctors to the Twig template
        return $this->render('liste_doctor/list.html.twig', [
            'doctors' => $doctors,
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function Profile(): Response
    {
        return $this->render('user/Profile/profile.html.twig');
    }
}