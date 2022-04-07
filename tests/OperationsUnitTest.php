<?php

namespace App\Tests;

use App\Entity\Operations;
use PHPUnit\Framework\TestCase;

class OperationsUnitTest extends TestCase
{
    public function testisTrue(): void
    {
        $operation = new Operations();

        $operation->setDescription('Description de testing');

        $this->assertTrue($operation->getDescription() === 'Description de testing');
    }

    public function testisFalse(): void
    {
        $operation = new Operations();

        $operation->setDescription('Description de testing');

        $this->assertFalse($operation->getDescription() === 'false');
    }

    public function testIsEmpty(): void
    {
        $operation = new Operations();

        $this->assertEmpty($operation->getDescription());
    }
}
