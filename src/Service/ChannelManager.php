<?php

namespace App\Service;

use App\Entity\Channel;
use Doctrine\ORM\EntityManagerInterface;

class ChannelManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getChannel(array $condition): ?Channel
    {
        return $this->em->getRepository(Channel::class)->findOneBy($condition);
    }

    public function create(array $fields): Channel
    {
        $channel = new Channel();
        $channel->setChannelId($fields['channelId']);
        $channel->setTitle($fields['title']);

        $this->em->persist($channel);
        $this->em->flush($channel);

        return $channel;
    }
}
