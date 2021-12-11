<?php

namespace App\Service;

use App\Entity\Tag;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;

class TagManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getTag(array $condition): ?Tag
    {
        return $this->em->getRepository(Tag::class)->findOneBy($condition);
    }

    public function create(Video $video, string $name): Tag
    {
        $tag = new Tag();
        $tag->setName($name);
        $tag->addVideo($video);

        $video->addTag($tag);

        $this->em->persist($tag);
        $this->em->flush($tag);

        return $tag;
    }
}
