<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\RatesPullingService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RatesPullCommand extends Command
{
    protected static $defaultName = 'rates:pull';

    private $ratesPullingService;

    public function __construct(RatesPullingService $ratesPullingService)
    {
        parent::__construct(static::$defaultName);
        $this->ratesPullingService = $ratesPullingService;
    }

    protected function configure(): void
    {
        $this->setDescription('Pull rates from all configured sources');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->ratesPullingService->sync($io);
        return 0;
    }
}
