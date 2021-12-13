<?php

namespace App\Service\Fake;

use App\Service\YoutubeServiceInterface;
use Google\Service\YouTube\Channel;
use Google\Service\YouTube\ChannelListResponse;
use Google\Service\YouTube\ChannelSnippet;
use Google\Service\YouTube\ResourceId;
use Google\Service\YouTube\SearchListResponse;
use Google\Service\YouTube\SearchResult;
use Google\Service\YouTube\Video;
use Google\Service\YouTube\VideoListResponse;
use Google\Service\YouTube\VideoSnippet;
use Google\Service\YouTube\VideoStatistics;

class FakeYoutubeService implements YoutubeServiceInterface
{
    public function getChannel(string $channelId)
    {
        $snippet = new ChannelSnippet();
        $snippet->setTitle('channel_title');

        $channel = new Channel();
        $channel->setId($channelId);    
        $channel->setSnippet($snippet);

        $channels = new ChannelListResponse();        
        $channels->setItems([$channel]);

        return $channels->getItems();
    }

    public function searchChannelVideos(string $channelId, string $pageToken = '')
    {
        $resourceId = new ResourceId();
        $resourceId->setVideoId('video_id');

        $searchResult = new SearchResult();
        $searchResult->setId($resourceId);

        $searchListResponse = new SearchListResponse();        
        $searchListResponse->setItems([$searchResult]);

        return $searchListResponse;
    }

    public function getVideoList(string $ids)
    {
        $videoSnippet = new VideoSnippet();
        $videoSnippet->setTags(['tag1', 'tag2']);
        $videoSnippet->setTitle('video_title');

        $videoStatistics = new VideoStatistics();
        $videoStatistics->setCommentCount(1);
        $videoStatistics->setDislikeCount(2);
        $videoStatistics->setFavoriteCount(3);
        $videoStatistics->setLikeCount(4);
        $videoStatistics->setViewCount(5);

        $video = new Video();
        $video->setId('video_id');        
        $video->setSnippet($videoSnippet);
        $video->setStatistics($videoStatistics);

        $videoListResponse = new VideoListResponse();
        $videoListResponse->setItems([$video]);

        return $videoListResponse;
    }
}
