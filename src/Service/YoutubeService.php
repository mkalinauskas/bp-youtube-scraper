<?php

namespace App\Service;

class YoutubeService
{
    const MAX_RESULTS = 50;

    /**
     * @var \Google_Service_YouTube client
     */
    private $client;

    /**
     * @param GoogleClient $googleClient
     */
    public function __construct(GoogleClient $googleClient)
    {
        $this->client = new \Google_Service_YouTube($googleClient->getClient());
    }

    /**
     * https://developers.google.com/youtube/v3/docs/channels/list
     */
    public function getChannel(string $channelId)
    {
        $response = $this->client->channels->listChannels(
            'snippet',
            [ 
                'id' => $channelId
            ]
        ); 

        return $response->getItems()[0];
    }

   /**
     * https://developers.google.com/youtube/v3/docs/search/list
     */    
    public function getChannelVideos(string $channelId, string $pageToken = '')
    {
        $params = [
            'channelId' => $channelId,
            'maxResults' => SELF::MAX_RESULTS,
            'type' => 'video',
            'order' => 'date'
        ];

        if ($pageToken) {
            $params['pageToken'] = $pageToken;
        }

        $response = $this->client->search->listSearch(
            'snippet',
            $params
        );

        return $response;
    }

   /**
     * https://developers.google.com/youtube/v3/docs/videos/list
     * @param string $ids 
     */   
    public function getVideoStatistics(string $ids)
    {
        $response = $this->client->videos->listVideos(
            'statistics,snippet',
            [
                'id' => $ids,
            ]
        );

        return $response->getItems();
    }     
}