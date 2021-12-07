<?php

namespace App\Service;

use App\Entity\Channel;

class YoutubeScraper
{
    private $youtubeService;

    private $channelManager;

    private $videoManager;

    private $tagManager;

    private $statisticManager;

    public function __construct(YoutubeService $youtubeService,
                                ChannelManager $channelManager,
                                VideoManager $videoManager,
                                TagManager $tagManager,
                                StatisticManager $statisticManager)
    {
        $this->youtubeService = $youtubeService;
        $this->channelManager = $channelManager;
        $this->videoManager = $videoManager;
        $this->tagManager = $tagManager;
        $this->statisticManager = $statisticManager;
    }

    /**
     * https://developers.google.com/youtube/v3/docs/channels/list
     * THROW EXCEPTION
     */
    public function scrapeChannel(string $channelId): Channel
    {
        $channel = $this->channelManager->getChannel(['channel_id' => $channelId]);

        if (!$channel) {
            $response = $this->youtubeService->getChannel($channelId);

            $channel = $this->channelManager->create(
                [
                    'channelId' => $response->getId(),
                    'title'     => $response->getSnippet()->getTitle(),                    
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
        $searchListResponse = $this->youtubeService->getChannelVideos($channel->getChannelId(), $pageToken);

        $ids = $this->generateVideoIdString($searchListResponse->getItems());

        $videoStatistics = $this->youtubeService->getVideoStatistics($ids);

        foreach($videoStatistics as $videoItem) {
            $video = $this->videoManager->getVideo(['video_id' => $videoItem->getId()]);

            if (!$video) {
                $video = $this->videoManager->create($channel, $videoItem->getSnippet()->getTitle(), $videoItem->getId());
            }

            if ($videoItem->getSnippet()->getTags()) {
                $tags = [];
                foreach ($videoItem->getSnippet()->getTags() as $item) {
                    $tag = $this->tagManager->getTag(['name' => $item]);
    
                    if (!$tag) {
                        $tag = $this->tagManager->create($video, $item);
                        $tags[] = $tag;
                    } 
                }               
            }

            $this->statisticManager->create(
                $video,
                [
                    'comment_count' => $videoItem->getStatistics()->getCommentCount(),
                    'dislike_count' => $videoItem->getStatistics()->getDislikeCount(),
                    'favorite_count' => $videoItem->getStatistics()->getFavoriteCount(),
                    'like_count' => $videoItem->getStatistics()->getLikeCount(),
                    'view_count' => $videoItem->getStatistics()->getViewCount()
                ]
            );         
        }

        if ($searchListResponse->getNextPageToken()) {
           return $this->scrapeChannelVideos($channel, $searchListResponse->getNextPageToken());
        }
       
       return;
    }  
    
    private function generateVideoIdString(array $videos)
    {
        $ids = [];
        foreach($videos as $video)
        {
            $ids[] = $video['id']['videoId'];
        }

        return implode(',', $ids);
    }
}