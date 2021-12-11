<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\YoutubeScraper;
use App\Service\PerformanceService;

#[AsCommand(
    name: 'app:scrape-youtube-channel',
    description: 'Scrape YouTube channel, its videos and statistics',
)]
class ScrapeYoutubeChannelCommand extends Command
{
    private $youtubeScraper;

    private $performanceService;

    public function __construct(
        YoutubeScraper $youtubeScraper,
        PerformanceService $performanceService
    ) {
        parent::__construct();
        $this->youtubeScraper = $youtubeScraper;
        $this->performanceService = $performanceService;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('channel_id', InputArgument::REQUIRED, 'YouTube channel id (eg. UCsBjURrPoezykLs9EqgamOA)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $channelId = $input->getArgument('channel_id');

        $io->info('Scraping started');

        try {
            $channel = $this->youtubeScraper->scrapeChannel($channelId);
            $this->youtubeScraper->scrapeChannelVideos($channel);
            $this->performanceService->updateChannelVideoPerformance($channel);
        } catch (\Exception $ex) {
            $io->error($ex->getMessage());
            return Command::FAILURE;
        }

        $io->info('Scraping for channel ' . $channelId . ' ended succesfully');

        return Command::SUCCESS;
    }
}
