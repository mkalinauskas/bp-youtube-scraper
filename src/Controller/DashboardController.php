<?php

namespace App\Controller;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Video;
use Doctrine\Persistence\ManagerRegistry;

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

    #[Route('api/tags', name: 'api.tags')]
    public function tags(ManagerRegistry $doctrine): Response
    {
        $tags = $doctrine->getRepository(Tag::class)
        ->findAll();

        $response = [];
        foreach ($tags as $tag){
            $response[] = $tag->getName();
        }

        return $this->json($response);
    }    
}
