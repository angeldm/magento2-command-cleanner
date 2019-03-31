<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angeldm\Cleanner\Console\Command;

use Magento\Framework\App\Cache;
use Magento\Framework\App\State\CleanupFiles;
use Magento\Framework\ObjectManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleannerCommand extends Command
{
    private $cache;
    private $cleanupFiles;
    private $objectManager;

    public function __construct(
        Cache $cache,
        CleanupFiles $cleanupFiles,
        ObjectManagerInterface $objectManager
      ) {
        $this->cache = $cache;
        $this->cleanupFiles = $cleanupFiles;
        $this->objectManager = $objectManager;
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
        $this->cleanup($input, $output);
    }

    protected function cleanup(InputInterface $input, OutputInterface $output)
    {
        $this->cache->clean();
        $output->writeln('<info>Cache cleared successfully.</info>');
        $this->cleanupFiles->clearCodeGeneratedClasses();
        $mode = $this->objectManager->create(\Magento\Deploy\Model\Mode::class, ['input' => $input,'output' => $output]);
        if ($mode->getMode() == \Magento\Framework\App\State::MODE_PRODUCTION) {
            $output->writeln("<info>Generated classes cleared successfully. Please run the 'setup:di:compile' command to generate classes.</info>");
        } else {
            $output->writeln("<info>Generated classes cleared successfully.</info>");
        }

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

    public function getModeObject()
    {
        return $this->objectManager->create(
            \Magento\Deploy\Model\Mode::class,
            [
            'input' => new \Symfony\Component\Console\Input\ArrayInput([]),
            'output' => $this->output,
        ]
    );
    }
}
