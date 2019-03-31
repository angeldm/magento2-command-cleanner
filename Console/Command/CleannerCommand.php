<?php

namespace Angeldm\Cleanner\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleannerCommand extends Command
{
    protected $cache;
    protected $cleanupFiles;

    public function __construct(
        \Magento\Framework\App\Cache $cache,
        \Magento\Framework\App\State\CleanupFiles $cleanupFiles
    ) {
        $this->cache = $cache;
        $this->cleanupFiles = $cleanupFiles;
        parent::__construct();
    }
    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $output->writeln("Hello ");
        $this->cleanup($output);
    }

    protected function cleanup(OutputInterface $output)
    {
        $this->cache->clean();
        $output->writeln('<info>Cache cleared successfully.</info>');
        $this->cleanupFiles->clearCodeGeneratedClasses();
            $output->writeln(
                "<info>Generated classes cleared successfully. "
                ."Please run the 'setup:di:compile' command to generate classes.</info>"
            );

        $this->cleanupFiles->clearMaterializedViewFiles();
        $output->writeln('<info>Generated static view files cleared successfully.</info>');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("cache:clean:static");
        $this->setDescription("Clean");
        parent::configure();
    }
}
