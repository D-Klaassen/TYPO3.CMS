<?php
namespace TYPO3\CMS\Belog\Tests\Unit\Domain\Model;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class ConstraintTest extends UnitTestCase
{
    /**
     * @var \TYPO3\CMS\Belog\Domain\Model\Constraint
     */
    protected $subject;

    protected function setUp()
    {
        $this->subject = new \TYPO3\CMS\Belog\Domain\Model\Constraint();
    }

    /**
     * @test
     */
    public function setManualDateStartForDateTimeSetsManualDateStart()
    {
        $date = new \DateTime();
        $this->subject->setManualDateStart($date);
        $this->assertEquals($date, $this->subject->getManualDateStart());
    }

    /**
     * @test
     */
    public function setManualDateStartForNoArgumentSetsManualDateStart()
    {
        $this->subject->setManualDateStart();
        $this->assertNull($this->subject->getManualDateStart());
    }

    /**
     * @test
     */
    public function setManualDateStopForDateTimeSetsManualDateStop()
    {
        $date = new \DateTime();
        $this->subject->setManualDateStop($date);
        $this->assertEquals($date, $this->subject->getManualDateStop());
    }

    /**
     * @test
     */
    public function setManualDateStopForNoArgumentSetsManualDateStop()
    {
        $this->subject->setManualDateStop();
        $this->assertNull($this->subject->getManualDateStop());
    }
}
