<?php

namespace App\Service;

/**
 * Class YoutubeService
 *
 * This is the google client that is used by almost every api
 */
class YoutubeService
{
    /**
     * @var \Google_Service_YouTube client
     */
    private $client;

    /**
     * @param array $config
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
}