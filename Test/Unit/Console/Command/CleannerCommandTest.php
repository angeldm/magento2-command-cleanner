<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angeldm\Cleanner\Test\Unit\Console\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Angeldm\Cleanner\Console\Command\CleannerCommand;

class CleannerCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\App\Cache|\PHPUnit_Framework_MockObject_MockObject
     */
    private $cache;
    /**
     * @var \Magento\Framework\App\State\CleanupFiles|\PHPUnit_Framework_MockObject_MockObject
     */

    private $cleanupFiles;
    /**
     * @var GreetingCommand
     */
    private $command;
    public function setUp()
    {
        $this->cache = $this->createMock(\Magento\Framework\App\Cache::class);
        $this->cleanupFiles = $this->createMock(\Magento\Framework\App\State\CleanupFiles::class);
	$this->command = new CleannerCommand(
            $this->cache,
            $this->cleanupFiles
	);
    }
    
    public function testExecuteName()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);
	$this->assertContains('Cache cleared successfully.', $commandTester->getDisplay());
	$this->assertContains('Generated classes cleared successfully.', $commandTester->getDisplay());
	$this->assertContains('Generated static view files cleared successfully.', $commandTester->getDisplay());
    }

}
