<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\PerformanceService;
use App\Service\ChannelManager;

#[AsCommand(
    name: 'app:update-video-performance',
    description: 'Add a short description for your command',
)]
class UpdateVideoPerformanceCommand extends Command
{
    private $performanceService;

    private $channelManager;

    public function __construct(PerformanceService $performanceService,
                                ChannelManager $channelManager)
    {
        parent::__construct();
        $this->performanceService = $performanceService;
        $this->channelManager = $channelManager;
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

        $channel = $this->channelManager->getChannel(['channel_id' => $channelId]);

        $this->performanceService->updateVideosPerformance($channel);
        
        return Command::SUCCESS;
    }
}
