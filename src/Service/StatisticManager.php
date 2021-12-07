<?php

namespace App\Service;

use App\Entity\Video;
use App\Entity\Statistic;
use Doctrine\ORM\EntityManagerInterface;

class StatisticManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(Video $video, array $fields): Statistic
    {
        $statistic = new Statistic();
        $statistic->setVideo($video);
        $statistic->setCommentCount($fields['comment_count']);
        $statistic->setDislikeCount($fields['dislike_count']);
        $statistic->setFavoriteCount($fields['favorite_count']);
        $statistic->setLikeCount($fields['like_count']);
        $statistic->setViewCount($fields['view_count']);

        $this->em->persist($statistic);
        $this->em->flush($statistic);

        return $statistic;
    }
}