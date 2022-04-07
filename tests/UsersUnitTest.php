<?php

namespace App\Tests;

use App\Entity\Users;
use PHPUnit\Framework\TestCase;

class UsersUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new Users();

        $user->setEmail('test@test.com')
            ->setPassword('testPassword');


        $this->assertTrue($user->getEmail() === 'test@test.com');
        $this->assertTrue($user->getPassword() === 'testPassword');

    }

    public function testIsFalse(): void
    {
        $user = new Users();

        $user->setEmail('test@test.com')
            ->setPassword('testPassword');


        $this->assertFalse($user->getEmail() === 'false');
        $this->assertFalse($user->getPassword() === 'false');

    }

    public function testIsEmpty(): void
    {
        $user = new Users();
        $user->setPassword('');

        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPassword());


    }
}
