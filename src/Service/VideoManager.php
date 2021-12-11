<?php

namespace App\Service;

use App\Entity\Video;
use App\Entity\Channel;
use Doctrine\ORM\EntityManagerInterface;

class VideoManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getVideo(array $condition): ?Video
    {
        return $this->em->getRepository(Video::class)->findOneBy($condition);
    }

    public function create(Channel $channel, string $title, string $video_id): Video
    {
        $video = new Video();
        $video->setChannel($channel);
        $video->setTitle($title);
        $video->setVideoId($video_id);

        $this->em->persist($video);
        $this->em->flush($video);

        return $video;
    }

    public function update(Video $video, $andFlush = false): void
    {
        $this->em->persist($video);

        if ($andFlush) {
            $this->em->flush();
        }
    }
}
