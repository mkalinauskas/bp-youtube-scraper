<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\YoutubeScraper;


#[AsCommand(
    name: 'app:scrape-youtube-channel',
    description: 'Add a short description for your command',
)]
class ScrapeYoutubeChannelCommand extends Command
{

    private $youtubeScraper;

    public function __construct(YoutubeScraper $youtubeScraper)
    {
        parent::__construct();
        $this->youtubeScraper = $youtubeScraper;
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

        $channel = $this->youtubeScraper->scrapeChannel($channelId);

        $this->youtubeScraper->scrapeChannelVideos($channel);

        return Command::SUCCESS;
    }
}
