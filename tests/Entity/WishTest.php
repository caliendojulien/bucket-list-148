<?php

namespace App\Tests\Entity;

use App\Entity\Wish;
use PHPUnit\Framework\TestCase;

class WishTest extends TestCase
{
    public function testGettersSetters() {
        $wish = new Wish();
        $wish->setTitle('Nouveau wish');

        $this->assertEquals("Nouveau wish", $wish->getTitle());
        $this->assertNotEquals("Nouveauwish", $wish->getTitle());
    }

    public function testPublication() {
        $wish = new Wish();
        $this->assertTrue($wish->isIsPublished());
    }
}
