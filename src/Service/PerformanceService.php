<?php

namespace App\Service;

use App\Entity\Channel;
use App\Entity\Statistic;
use Doctrine\ORM\EntityManagerInterface;

class PerformanceService
{
    private $videoManager;

    private $em;

    public function __construct(
        VideoManager $videoManager,
        EntityManagerInterface $em
    ) {
        $this->videoManager = $videoManager;
        $this->em = $em;
    }

    public function updateChannelVideoPerformance(Channel $channel): void
    {
        $videos = $channel->getVideos();
        $channelFirstHourViews = [];
        foreach ($videos as $video) {
            $videoFirstHourViews = $this->em->getRepository(Statistic::class)->findFirstHourVideoViews($video);

            if (count($videoFirstHourViews) > 1) {
                $lastElement = end($videoFirstHourViews)['view_count'];
                $firstElement = $videoFirstHourViews[0]['view_count'];
                $views = $lastElement - $firstElement;
                $channelFirstHourViews[$video->getVideoId()] = $views;
            }
        }

        $channelMedian = $this->getMedianNumberFromArray(array_values($channelFirstHourViews));

        foreach ($videos as $video) {
            $video->setPerformance(0);
            if (isset($channelFirstHourViews[$video->getVideoId()])) {
                $video->setPerformance($channelFirstHourViews[$video->getVideoId()] / $channelMedian);
            }
            $this->videoManager->update($video, true);
        }

        return;
    }

    private function getMedianNumberFromArray(array $arr): int
    {
        sort($arr);
        $num = count($arr);
        $middleVal = floor(($num - 1) / 2);
        if ($num % 2) {
            return $arr[$middleVal];
        } else {
            $lowMid = $arr[$middleVal];
            $highMid = $arr[$middleVal + 1];

            return (($lowMid + $highMid) / 2);
        }
    }
}
