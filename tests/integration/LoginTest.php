<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\TestCase;
use NeoTransposer\Domain\Entity\User;
use NeoTransposer\Domain\Repository\BookRepository;
use NeoTransposer\Domain\Repository\UserRepository;
use NeoTransposer\Domain\ValueObject\NotesRange;
use NeoTransposer\Domain\ValueObject\UserPerformance;

final class LoginTest extends TestCase
{
    public function testLoginInEnglishRedirectsToEnglishBookForExistingUser(): void
    {
        $existingUser = new User(
            email: 'user@example.com',
            id_user: 42,
            range: new NotesRange('C2', 'G3'),
            id_book: 6, // Originally French book
            performance: new UserPerformance(0, 0)
        );

        $bookRepositoryMock = $this->createMock(BookRepository::class);
        $bookRepositoryMock->method('readIdBookFromLocale')
            ->with('en')
            ->willReturn(3);

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->method('readFromEmail')
            ->with('user@example.com')
            ->willReturn($existingUser);

        $savedUser = null;
        $userRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (User $u) use (&$savedUser) {
                $savedUser = $u;
                return $u->id_user;
            });

        $this->app->instance(BookRepository::class, $bookRepositoryMock);
        $this->app->instance(UserRepository::class, $userRepositoryMock);

        $response = $this->post('/en/login', [
            'email' => 'user@example.com',
        ]);

        // English book (id_book=3) route is /songs-neocatechumenal-way (name: book_3)
        $response->assertRedirect(route('book_3'));
        $this->assertNotNull($savedUser);
        $this->assertSame(3, $savedUser->id_book);
        $this->assertSame(3, session('user')->id_book);
    }

    public function testLoginInFrenchRedirectsToFrenchBookForExistingUser(): void
    {
        $existingUser = new User(
            email: 'user@example.com',
            id_user: 42,
            range: new NotesRange('C2', 'G3'),
            id_book: 3, // Originally English book
            performance: new UserPerformance(0, 0)
        );

        $bookRepositoryMock = $this->createMock(BookRepository::class);
        $bookRepositoryMock->method('readIdBookFromLocale')
            ->with('fr')
            ->willReturn(6);

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->method('readFromEmail')
            ->with('user@example.com')
            ->willReturn($existingUser);

        $savedUser = null;
        $userRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (User $u) use (&$savedUser) {
                $savedUser = $u;
                return $u->id_user;
            });

        $this->app->instance(BookRepository::class, $bookRepositoryMock);
        $this->app->instance(UserRepository::class, $userRepositoryMock);

        $response = $this->post('/fr/login', [
            'email' => 'user@example.com',
        ]);

        // French book (id_book=6) route is /chants-chemin-neocatechumenal (name: book_6)
        $response->assertRedirect(route('book_6'));
        $this->assertNotNull($savedUser);
        $this->assertSame(6, $savedUser->id_book);
        $this->assertSame(6, session('user')->id_book);
    }
}
