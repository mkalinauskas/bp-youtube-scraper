<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Video;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $videos = $doctrine->getRepository(Video::class)
        ->findBy([], ['performance' => 'DESC']);

        return $this->render('dashboard.html.twig', [
            'videos' => $videos
        ]);
    }
}
