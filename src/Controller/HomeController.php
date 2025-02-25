<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function listDoctors(): Response
    {
        return $this->render('liste_doctor/list.html.twig');
    }

    #[Route('/profile', name: 'profile')]
    public function Profile(): Response
    {
        return $this->render('user/Profile/profile.html.twig');
    }

}
