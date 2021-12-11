<?php

namespace App\Service;

use App\Entity\Channel;
use App\Exception\ChannelNotFoundException;

class YoutubeScraper
{
    private $youtubeService;

    private $channelManager;

    private $videoManager;

    private $tagManager;

    private $statisticManager;

    public function __construct(
        YoutubeService $youtubeService,
        ChannelManager $channelManager,
        VideoManager $videoManager,
        TagManager $tagManager,
        StatisticManager $statisticManager
    ) {
        $this->youtubeService = $youtubeService;
        $this->channelManager = $channelManager;
        $this->videoManager = $videoManager;
        $this->tagManager = $tagManager;
        $this->statisticManager = $statisticManager;
    }

    /**
     * https://developers.google.com/youtube/v3/docs/channels/list
     *
     * @throws ChannelNotFoundException
     */
    public function scrapeChannel(string $channelId): Channel
    {
        $channel = $this->channelManager->getChannel(['channel_id' => $channelId]);

        if (!$channel) {
            $response = $this->youtubeService->getChannel($channelId);

            if (empty($response)) {
                throw new ChannelNotFoundException();
            }

            $channel = $this->channelManager->create(
                [
                    'channelId' => $response[0]->getId(),
                    'title'     => $response[0]->getSnippet()->getTitle(),
                ]
            );
        }

        return $channel;
    }

   /**
     * https://developers.google.com/youtube/v3/docs/search/list
     */
    public function scrapeChannelVideos(Channel $channel, string $pageToken = '')
    {
        $searchListResponse = $this->youtubeService->searchChannelVideos($channel->getChannelId(), $pageToken);

        $ids = $this->generateVideoIdString($searchListResponse->getItems());

        $videoListResponse = $this->youtubeService->getVideoList($ids);

        foreach ($videoListResponse as $listItem) {
            $video = $this->videoManager->getVideo(['video_id' => $listItem->getId()]);

            if (!$video) {
                $video = $this->videoManager->create($channel, $listItem->getSnippet()->getTitle(), $listItem->getId());
            }

            if ($listItem->getSnippet()->getTags()) {
                foreach ($listItem->getSnippet()->getTags() as $item) {
                    $tag = $this->tagManager->getTag(['name' => $item]);

                    if (!$tag) {
                        $tag = $this->tagManager->create($video, $item);
                    }
                }
            }

            if ($listItem->getStatistics()) {
                $this->statisticManager->create(
                    $video,
                    [
                        'comment_count' => $listItem->getStatistics()->getCommentCount(),
                        'dislike_count' => $listItem->getStatistics()->getDislikeCount(),
                        'favorite_count' => $listItem->getStatistics()->getFavoriteCount(),
                        'like_count' => $listItem->getStatistics()->getLikeCount(),
                        'view_count' => $listItem->getStatistics()->getViewCount()
                    ]
                );
            }
        }

        if ($searchListResponse->getNextPageToken()) {
            return $this->scrapeChannelVideos($channel, $searchListResponse->getNextPageToken());
        }

        return;
    }

    private function generateVideoIdString(array $videos): string
    {
        $ids = [];
        foreach ($videos as $video) {
            $ids[] = $video->getId()->getVideoId();
        }

        return implode(',', $ids);
    }
}
