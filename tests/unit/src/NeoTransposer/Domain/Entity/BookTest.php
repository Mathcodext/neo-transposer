<?php

namespace NeoTransposer\Tests\Domain\Entity;

use NeoTransposer\Domain\Entity\Book;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    public function testBookGetters(): void
    {
        $book = new Book(6, 'Français', 'Paris 2023', 'French', 'fr', 210);

        $this->assertEquals(6, $book->idBook());
        $this->assertEquals('Français', $book->langName());
        $this->assertEquals('Paris 2023', $book->details());
        $this->assertEquals('fr', $book->locale());
        $this->assertEquals(210, $book->songCount());
    }
}
