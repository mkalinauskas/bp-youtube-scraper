<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Tag;

class TagController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    #[Route('api/tags', name: 'api.tags')]
    public function tags(ManagerRegistry $doctrine): Response
    {
        $tags = $doctrine->getRepository(Tag::class)
        ->findAll();

        $tagsJson = $this->serializer->serialize(
            $tags,
            'json',
            ['groups' => 'list_tags']
        );

        return new Response($tagsJson);
    }
}
