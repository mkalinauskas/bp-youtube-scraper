<?php

namespace App\Service;

interface YoutubeServiceInterface
{
    public function getChannel(string $channelId);

    public function searchChannelVideos(string $channelId, string $pageToken = '');

    public function getVideoList(string $ids);
}