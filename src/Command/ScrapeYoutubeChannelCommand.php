<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\YoutubeService;
use App\Entity\Channel;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:scrape-youtube-channel',
    description: 'Add a short description for your command',
)]
class ScrapeYoutubeChannelCommand extends Command
{

    private $youtubeService;

    public function __construct(YoutubeService $youtubeService, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->youtubeService = $youtubeService;
        $this->em = $em;
    }    

    protected function configure(): void
    {
        $this
            ->addArgument('channel_id', InputArgument::REQUIRED, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $channelId = $input->getArgument('channel_id');

        $response = $this->youtubeService->getChannel($channelId);

        $channel = new Channel();
        $channel->setTitle($response->getSnippet()->getTitle());
        $channel->setChannelId($response->getId());

        $this->em->persist($channel);
        $this->em->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
