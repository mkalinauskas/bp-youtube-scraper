<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Video;
use App\Entity\Statistic;

/**
 * @method Statistic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statistic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statistic[]    findAll()
 * @method Statistic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistic::class);
    }

    public function findFirstHourVideoViews(Video $video)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT view_count
                FROM statistic
                WHERE video_id = :video_id AND timestampdiff(second, :video_created_at, created_at)/3600 <= 2';

        $stmt = $conn->prepare($sql);
        $result = $stmt->execute(
            [
                'video_id' => $video->getId(),
                'video_created_at' => $video->getCreatedAt()->format('Y-m-d H:i:s')
            ]
        );

        return $result->fetchAllAssociative();
    }
}
