<?php
/**
 * PHP version 7.3
 * tests/Entity/UserTest.php
 *
 * @category EntityTests
 * @package  MiW\Results\Tests
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

namespace MiW\Results\Tests\Entity;

use MiW\Results\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 *
 * @package MiW\Results\Tests\Entity
 * @group   users
 */
class UserTest extends TestCase
{
    /**
     * @var User $user
     */
    private $user;
    private const NOMBRE_USER = 'nameuser';
    private const EMAIL_USER = 'emailuser';

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->user = new User();
    }

    /**
     * @covers \MiW\Results\Entity\User::__construct()
     */
    public function testConstructor(): void
    {
        $user = new User(self::NOMBRE_USER, self::EMAIL_USER);
        self::assertSame(0, $user->getId());
        self::assertEquals(self::NOMBRE_USER, $user->getUsername());
        self::assertFalse($user->isEnabled());
        self::assertFalse($user->isAdmin());
    }

    /**
     * @covers \MiW\Results\Entity\User::getId()
     */
    public function testGetId(): void
    {
        $user = new User(self::NOMBRE_USER, self::EMAIL_USER);
        self::assertSame(0, $user->getId());
    }

    /**
     * @covers \MiW\Results\Entity\User::setUsername()
     * @covers \MiW\Results\Entity\User::getUsername()
     */
    public function testGetSetUsername(): void
    {
        $user = new User(self::NOMBRE_USER, self::EMAIL_USER);
        self::assertEquals(self::NOMBRE_USER, $user->getUsername());
    }

    /**
     * @covers \MiW\Results\Entity\User::getEmail()
     * @covers \MiW\Results\Entity\User::setEmail()
     */
    public function testGetSetEmail(): void
    {
        $user = new User(self::NOMBRE_USER, self::EMAIL_USER);
        self::assertEquals(self::EMAIL_USER, $user->getEmail());
    }

    /**
     * @covers \MiW\Results\Entity\User::setEnabled()
     * @covers \MiW\Results\Entity\User::isEnabled()
     */
    public function testIsSetEnabled(): void
    {
        $user = new User(self::NOMBRE_USER, self::EMAIL_USER);
        self::assertFalse($user->isEnabled());
    }

    /**
     * @covers \MiW\Results\Entity\User::setIsAdmin()
     * @covers \MiW\Results\Entity\User::isAdmin
     */
    public function testIsSetAdmin(): void
    {
        $user = new User(self::NOMBRE_USER, self::EMAIL_USER);
        self::assertFalse($user->isAdmin());
    }

    /**
     * @covers \MiW\Results\Entity\User::setPassword()
     * @covers \MiW\Results\Entity\User::validatePassword()
     */
    public function testSetValidatePassword(): void
    {
        $user = new User(self::NOMBRE_USER, self::EMAIL_USER, 'pass');
        self::assertFalse($user->validatePassword($user->setPassword('pass')));
    }

    /**
     * @covers \MiW\Results\Entity\User::__toString()
     */
    public function testToString(): void
    {
        $user = new User(self::NOMBRE_USER, self::EMAIL_USER);
        self::assertEquals(self::NOMBRE_USER, $user->getUsername());
    }

    /**
     * @covers \MiW\Results\Entity\User::jsonSerialize()
     */
    public function testJsonSerialize(): void
    {
        $user = new User(self::NOMBRE_USER, self::EMAIL_USER);
        self::assertEquals("0" . self::NOMBRE_USER . self::EMAIL_USER,
            implode("", $user->jsonSerialize()));
    }
}
