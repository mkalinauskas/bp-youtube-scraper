<?php

namespace App\Tests\Feature;

use App\Entity\Channel;
use App\Entity\Video;
use App\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ScrapeYoutubeChannelCommandTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
    }

    public function testCommandInsert()
    {
        $application = new Application(self::$kernel);

        $command = $application->find('app:scrape-youtube-channel');

        $commandTester = new CommandTester($command);

        $channelId = 'channelId';

        $commandTester->execute([
            'channel_id' => $channelId
        ]);

        $channelRepo = $this->entityManager->getRepository(Channel::class);
        $channel = $channelRepo->findOneBy(['channel_id' => $channelId]);

        $this->assertEquals('channelId', $channel->getChannelId());
        $this->assertEquals('channel_title', $channel->getTitle());

        $videoRepo = $this->entityManager->getRepository(Video::class);
        $videos = $videoRepo->findBy(['channel' => $channel]);

        $this->assertCount(1, $videos);
        $this->assertEquals($videos[0]->getTitle(), 'video_title');
        $this->assertCount(2, $videos[0]->getTags());
    }
}
